<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Enums\OrderHistoryActionEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderReturnHelper;
use Botble\Ecommerce\Http\Requests\OrderReturnRequest;
use Botble\Ecommerce\Http\Resources\API\OrderDetailResource;
use Botble\Ecommerce\Http\Resources\API\OrderReturnResource;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderReturnController extends BaseApiController
{
    /**
     * Get list of order return requests for the current user
     *
     * @group Order Returns
     * @param Request $request
     * @return mixed
     *
     * @authenticated
     */
    public function index(Request $request)
    {
        abort_unless(EcommerceHelper::isOrderReturnEnabled(), 404);

        $user = $request->user();

        $orderReturns = OrderReturn::query()
            ->where('user_id', $user->id)->latest()
            ->withCount('items')
            ->with(['items', 'order', 'latestHistory'])
            ->paginate($request->integer('per_page', 10));

        return $this
            ->httpResponse()
            ->setData(OrderReturnResource::collection($orderReturns))
            ->toApiResponse();
    }

    /**
     * Get detail of an order return request
     *
     * @group Order Returns
     * @param int|string $id
     * @param Request $request
     * @return mixed
     *
     * @authenticated
     */
    public function show(int|string $id, Request $request)
    {
        abort_unless(EcommerceHelper::isOrderReturnEnabled(), 404);

        $user = $request->user();

        $orderReturn = OrderReturn::query()
            ->where([
                'id' => $id,
                'user_id' => $user->id,
            ])
            ->with(['items', 'order', 'latestHistory'])
            ->firstOrFail();

        return $this
            ->httpResponse()
            ->setData(new OrderReturnResource($orderReturn))
            ->toApiResponse();
    }

    /**
     * Submit a new order return request
     *
     * @group Order Returns
     * @param OrderReturnRequest $request
     * @return mixed
     *
     * @authenticated
     *
     * @bodyParam order_id integer required The ID of the order to return. Example: 1
     * @bodyParam reason string required The reason for the return. Example: DAMAGED_PRODUCT
     * @bodyParam return_items array required The items to return with order_item_id, is_return, and qty.
     * @bodyParam return_items.*.order_item_id integer required The ID of the order item. Example: 1
     * @bodyParam return_items.*.is_return boolean required Whether to return this item. Example: true
     * @bodyParam return_items.*.qty integer The quantity to return (required if partial return is enabled). Example: 1
     * @bodyParam return_items.*.reason string The reason for returning this item (required if partial return is enabled). Example: DAMAGED_PRODUCT
     */
    public function store(OrderReturnRequest $request)
    {
        abort_unless(EcommerceHelper::isOrderReturnEnabled(), 404);

        /**
         * @var Order $order
         */
        $order = Order::query()
            ->where([
                'id' => $request->input('order_id'),
                'user_id' => $request->user()->id,
            ])
            ->firstOrFail();

        if (! $order->canBeReturned()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('You cannot return this order'))
                ->toApiResponse();
        }

        if ($reason = $request->input('reason')) {
            $orderReturnData['reason'] = $reason;
        }

        $orderReturnData['items'] = Arr::where(
            $request->input('return_items'),
            fn ($value) => isset($value['is_return'])
        );

        if (empty($orderReturnData['items'])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Please select at least 1 product to return!'))
                ->toApiResponse();
        }

        $totalRefundAmount = $order->amount - $order->shipping_amount;
        $totalPriceProducts = $order->products->sum(function ($item) {
            return $item->total_price_with_tax;
        });
        $ratio = $totalRefundAmount <= 0 ? 0 : $totalPriceProducts / $totalRefundAmount;

        foreach ($orderReturnData['items'] as &$item) {
            $orderProductId = Arr::get($item, 'order_item_id');
            if (! $orderProduct = $order->products->firstWhere('id', $orderProductId)) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage(__('Oops! Something Went Wrong.'))
                    ->toApiResponse();
            }
            $qty = $orderProduct->qty;
            if (EcommerceHelper::allowPartialReturn()) {
                $qty = (int) Arr::get($item, 'qty') ?: $qty;
                $qty = min($qty, $orderProduct->qty);
            }
            $item['qty'] = $qty;
            $item['refund_amount'] = $ratio == 0 ? 0 : ($orderProduct->price_with_tax * $qty / $ratio);
        }

        [$status, $data, $message] = OrderReturnHelper::returnOrder($order, $orderReturnData);

        if (! $status) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($message ?: __('Failed to return the order'))
                ->toApiResponse();
        }

        OrderHistory::query()->create([
            'action' => OrderHistoryActionEnum::RETURN_ORDER,
            'description' => __(':customer has requested return product(s)', ['customer' => $order->address->name]),
            'order_id' => $order->getKey(),
        ]);

        return $this
            ->httpResponse()
            ->setData(new OrderReturnResource($data))
            ->setMessage(__('Order returned successfully'))
            ->toApiResponse();
    }

    /**
     * Get order information for return request
     *
     * @group Order Returns
     * @param int|string $orderId
     * @param Request $request
     * @return mixed
     *
     * @authenticated
     */
    public function getReturnOrder(int|string $orderId, Request $request)
    {
        abort_unless(EcommerceHelper::isOrderReturnEnabled(), 404);

        $order = Order::query()
            ->where([
                'id' => $orderId,
                'user_id' => $request->user()->id,
            ])
            ->with(['products', 'shipment'])
            ->firstOrFail();

        abort_unless($order->canBeReturned(), 403, __('You cannot return this order'));

        $returnableItems = OrderReturnHelper::getReturnableItems($order);

        return $this
            ->httpResponse()
            ->setData([
                'order' => new OrderDetailResource($order),
                'returnable_items' => $returnableItems,
                'return_reasons' => OrderReturnHelper::getReturnReasons(),
            ])
            ->toApiResponse();
    }
}
