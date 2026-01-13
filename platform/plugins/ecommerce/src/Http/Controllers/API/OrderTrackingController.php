<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\API\OrderTrackingRequest;
use Botble\Ecommerce\Models\Order;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

/**
 * @group Orders
 *
 * APIs for order tracking
 */
class OrderTrackingController extends BaseApiController
{
    /**
     * Track an order
     *
     * Track an order by order code and email/phone
     *
     * @bodyParam code string required The order code. Example: ORD-12345
     * @bodyParam email string required if phone not provided The email associated with the order. Example: customer@example.com
     * @bodyParam phone string required if email not provided The phone number associated with the order. Example: +1234567890
     *
     * @response 200 {
     *     "error": false,
     *     "message": "Order found successfully",
     *     "data": {
     *         "order": {
     *             "id": 1,
     *             "code": "ORD-12345",
     *             "status": "completed",
     *             "amount": 100.00,
     *             "shipping_amount": 10.00,
     *             "payment_fee": 5.00,
     *             "tax_amount": 5.00,
     *             "sub_total": 90.00,
     *             "discount_amount": 0.00,
     *             "payment_id": 1,
     *             "user_id": 1,
     *             "created_at": "2023-08-10T12:34:56.000000Z",
     *             "updated_at": "2023-08-10T12:34:56.000000Z",
     *             "address": {
     *                 "id": 1,
     *                 "name": "John Doe",
     *                 "email": "customer@example.com",
     *                 "phone": "+1234567890",
     *                 "address": "123 Main St",
     *                 "city": "New York",
     *                 "state": "NY",
     *                 "country": "US",
     *                 "zip_code": "10001"
     *             },
     *             "products": [
     *                 {
     *                     "id": 1,
     *                     "name": "Product 1",
     *                     "price": 90.00,
     *                     "qty": 1
     *                 }
     *             ],
     *             "histories": [
     *                 {
     *                     "id": 1,
     *                     "action": "create_order",
     *                     "description": "Order was created",
     *                     "created_at": "2023-08-10T12:34:56.000000Z"
     *                 }
     *             ],
     *             "shipment": {
     *                 "id": 1,
     *                 "status": "delivered",
     *                 "tracking_id": "SHIP-12345",
     *                 "tracking_link": "https://example.com/tracking/SHIP-12345"
     *             },
     *             "payment": {
     *                 "id": 1,
     *                 "status": "completed",
     *                 "payment_channel": "stripe",
     *                 "amount": 100.00
     *             }
     *         }
     *     }
     * }
     *
     * @response 404 {
     *     "error": true,
     *     "message": "Order not found",
     *     "code": 404
     * }
     *
     * @param OrderTrackingRequest $request
     * @return JsonResponse
     */
    public function __invoke(OrderTrackingRequest $request)
    {
        if (! EcommerceHelper::isOrderTrackingEnabled()) {
            return $this->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage(__('Order tracking is not enabled'))
                ->toApiResponse();
        }

        $code = $request->input('code');

        $query = Order::query()
            ->where(function (Builder $query) use ($code): void {
                $query
                    ->where('ec_orders.code', $code)
                    ->orWhere('ec_orders.code', '#' . $code);
            })
            ->with([
                'address',
                'products' => function ($query): void {
                    $query
                        ->select([
                            'order_id',
                            'product_name',
                            'product_image',
                            'qty',
                            'price',
                        ]);
                },
                'histories' => function ($query): void {
                    $query
                        ->select(['order_id', 'action', 'description', 'created_at']);
                },
                'shipment',
                'payment',
            ])
            ->select('ec_orders.*')
            ->when(EcommerceHelper::isOrderTrackingUsingPhone(), function (BaseQueryBuilder $query) use ($request): void {
                $query->where(function (BaseQueryBuilder $query) use ($request): void {
                    $query
                        ->whereHas('address', fn ($subQuery) => $subQuery->where('phone', $request->input('phone')))
                        ->orWhereHas('user', fn ($subQuery) => $subQuery->where('phone', $request->input('phone')));
                });
            }, function (BaseQueryBuilder $query) use ($request): void {
                $query->where(function (Builder $query) use ($request): void {
                    $query
                        ->whereHas('address', fn ($subQuery) => $subQuery->where('email', $request->input('email')))
                        ->orWhereHas('user', fn ($subQuery) => $subQuery->where('email', $request->input('email')));
                });
            });

        $order = apply_filters('ecommerce_order_tracking_query', $query)->first();

        if (! $order) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage(__('Order not found'))
                ->toApiResponse();
        }

        $data = $order->toArray();

        foreach ($data['products'] as &$product) {
            $product['product_image'] = RvMedia::getImageUrl($product['product_image']);
            $product['price_formatted'] = format_price($product['price']);
        }

        return $this
            ->httpResponse()
            ->setData([
                'order' => $data,
            ])
            ->setMessage(__('Order found successfully'))
            ->toApiResponse();
    }
}
