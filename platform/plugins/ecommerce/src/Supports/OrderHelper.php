<?php

namespace Botble\Ecommerce\Supports;

use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\Html;
use Botble\Base\Supports\EmailHandler as EmailHandlerSupport;
use Botble\Base\Supports\Pdf;
use Botble\Ecommerce\Enums\OrderAddressTypeEnum;
use Botble\Ecommerce\Enums\OrderHistoryActionEnum;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Events\OrderCompletedEvent;
use Botble\Ecommerce\Events\OrderConfirmedEvent;
use Botble\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Events\OrderProductCreatedEvent;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Exceptions\ProductIsNotActivatedYetException;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\Discount;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\EcommerceHelper as EcommerceHelperFacade;
use Botble\Ecommerce\Facades\FlashSale;
use Botble\Ecommerce\Facades\InvoiceHelper as InvoiceHelperFacade;
use Botble\Ecommerce\Http\Requests\CheckoutRequest;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Option;
use Botble\Ecommerce\Models\OptionValue;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Models\ShipmentHistory;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Services\Footprints\FootprinterInterface;
use Botble\Media\Facades\RvMedia;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Facades\PaymentMethods;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentFeeHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class OrderHelper
{
    public function processOrder(string|array|null $orderIds, ?string $chargeId = null): bool|Collection|array|Model
    {
        if (! empty($data['is_refund_update'])) {
            return false;
        }

        $orderIds = (array) $orderIds;

        $orders = Order::query()->whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            return false;
        }

        if (is_plugin_active('payment') && $chargeId) {
            $payments = Payment::query()
                ->where('charge_id', $chargeId)
                ->whereIn('order_id', $orderIds)
                ->get();

            if ($payments->isNotEmpty()) {
                foreach ($orders as $order) {
                    $payment = $payments->firstWhere('order_id', $order->getKey());
                    if ($payment) {
                        $order->payment_id = $payment->getKey();
                        $order->save();
                    }
                }
            }
        }

        foreach ($orders as $order) {
            /**
             * @var Order $order
             */
            if (
                (float) $order->amount
                && (is_plugin_active('payment') && ! empty(PaymentMethods::methods()) && ! $order->payment_id)
            ) {
                continue;
            }

            event(new OrderPlacedEvent($order));

            if (EcommerceHelper::isOrderAutoConfirmedEnabled()) {
                $this->confirmOrder($order);
            }

            $order->is_finished = true;
            $order->save();

            $this->decreaseProductQuantity($order);
        }

        Cart::instance('cart')->destroy();
        session()->forget('applied_coupon_code');

        session(['order_id' => Arr::first($orderIds)]);

        /**
         * @var Order $firstOrder
         */
        $firstOrder = $orders->first();

        if (is_plugin_active('marketplace')) {
            apply_filters(SEND_MAIL_AFTER_PROCESS_ORDER_MULTI_DATA, $orders);
        } else {
            if (
                ! is_plugin_active('payment')
                || in_array($order->payment->status, [PaymentStatusEnum::PENDING, PaymentStatusEnum::COMPLETED])
            ) {
                $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
                if ($mailer->templateEnabled('admin_new_order')) {
                    $mailer = $this->setEmailVariables($firstOrder, $mailer);
                    $mailer->sendUsingTemplate('admin_new_order');
                }

                // Temporarily only send emails with the first order
                $this->sendOrderConfirmationEmail($firstOrder, true);
            }
        }

        session(['order_id' => $firstOrder->getKey()]);

        foreach ($orders as $order) {
            OrderHistory::query()->create([
                'action' => OrderHistoryActionEnum::CREATE_ORDER,
                'description' => trans('plugins/ecommerce::order.new_order_from', [
                    'order_id' => $order->code,
                    'customer' => BaseHelper::clean($order->user->name ?: $order->address->name),
                ]),
                'order_id' => $order->id,
            ]);

            if (
                (
                    is_plugin_active('payment')
                    && $order->amount
                    && $order->payment
                    && $order->payment->status == PaymentStatusEnum::COMPLETED
                )
                || $order->amount == 0
            ) {
                /**
                 * @var Order $order
                 */
                if (EcommerceHelperFacade::isEnabledSupportDigitalProducts()) {
                    $digitalProductsCount = EcommerceHelperFacade::countDigitalProducts($order->products);

                    if ($digitalProductsCount === $order->products->count() && EcommerceHelperFacade::isAutoCompleteDigitalOrdersAfterPayment()) {
                        $this->setOrderCompleted($order->getKey(), request(), Auth::id() ?? 0);
                    } elseif ($digitalProductsCount > 0) {
                        event(new OrderCompletedEvent($order));
                    }
                }
            }
        }

        if (FlashSale::isEnabled()) {
            foreach ($orders as $order) {
                foreach ($order->products as $orderProduct) {
                    $product = $orderProduct->product->original_product;

                    $flashSale = $product->latestFlashSales()->first();
                    if (! $flashSale) {
                        continue;
                    }

                    $flashSale->products()->detach([$product->id]);
                    $flashSale->products()->attach([
                        $product->id => [
                            'price' => $flashSale->pivot->price,
                            'quantity' => (int) $flashSale->pivot->quantity,
                            'sold' => (int) $flashSale->pivot->sold + $orderProduct->qty,
                        ],
                    ]);
                }
            }
        }

        return $orders;
    }

    public function decreaseProductQuantity(Order $order): bool
    {
        foreach ($order->products as $orderProduct) {
            /**
             * @var Product $product
             */
            $product = Product::query()->find($orderProduct->product_id);

            if (! $product) {
                continue;
            }

            // Only decrease quantity if storehouse management is enabled
            if ($product->with_storehouse_management && $product->quantity >= $orderProduct->qty) {
                $product->quantity = $product->quantity - $orderProduct->qty;
                $product->save();

                // Trigger event to update parent product if this is a variation
                event(new ProductQuantityUpdatedEvent($product));
            }
        }

        return true;
    }

    public function setEmailVariables(Order $order, ?EmailHandlerSupport $emailHandler = null): EmailHandlerSupport
    {
        $emailHandler = $emailHandler ?: EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);

        return $emailHandler->setVariableValues($this->getEmailVariables($order));
    }

    public function getEmailVariables(Order $order): array
    {
        $paymentMethod = '&mdash;';

        if (is_plugin_active('payment')) {
            $paymentMethod = $order->payment->payment_channel->label();

            if ($order->payment->payment_channel == PaymentMethodEnum::BANK_TRANSFER && $order->payment->status == PaymentStatusEnum::PENDING) {
                $paymentMethod .= '<div>' . trans('plugins/ecommerce::order.payment_info') . ': <strong>' .
                    BaseHelper::clean(get_payment_setting('description', $order->payment->payment_channel)) .
                    '</strong</div>';
            }
        }

        $digitalProducts = [];

        if (EcommerceHelperFacade::isEnabledSupportDigitalProducts()) {
            foreach ($order->digitalProducts() as $digitalProduct) {
                $digitalProducts[] = [
                    'product_name' => $digitalProduct->product_name,
                    'product_image_url' => $digitalProduct->product_image_url,
                    'product_file_internal_count' => $digitalProduct->product_file_internal_count,
                    'download_hash_url' => $digitalProduct->download_hash_url,
                    'product_file_external_count' => $digitalProduct->product_file_external_count,
                    'download_external_url' => $digitalProduct->download_external_url,
                    'product_attributes_text' => Arr::get($digitalProduct->options, 'attributes'),
                    'product_options_text' => $digitalProduct->product_options_implode,
                    'product_options_array' => $digitalProduct->product_options_array,
                    'license_code' => $digitalProduct->license_code,
                ];
            }
        }

        return apply_filters('ecommerce_order_email_variables', [
            'store_address' => get_ecommerce_setting('store_address'),
            'store_phone' => get_ecommerce_setting('store_phone'),
            'order_id' => $order->code,
            'order_token' => $order->token,
            'order_note' => $order->description,
            'customer_name' => BaseHelper::clean($order->user->name ?: $order->address->name),
            'customer_email' => $order->user->email ?: $order->address->email,
            'customer_phone' => $order->user->phone ?: $order->address->phone,
            'customer_address' => $order->full_address,
            'product_list' => view('plugins/ecommerce::emails.partials.order-detail', compact('order'))
                ->render(),
            'digital_product_list' => EcommerceHelperFacade::isEnabledSupportDigitalProducts() ? $this->getDigitalProductListView($order) : null,
            'shipping_method' => $order->shipping_method_name,
            'payment_method' => $paymentMethod,
            'order_delivery_notes' => view(
                'plugins/ecommerce::emails.partials.order-delivery-notes',
                compact('order')
            )
                ->render(),
            'order' => [
                ...$order->toArray(),
                'created_at' => $order->created_at->toDateTimeString(),
                'updated_at' => $order->updated_at->toDateTimeString(),
            ],
            'shipment' => $order->shipment ? [
                ...$order->shipment->toArray(),
                'created_at' => $order->shipment->created_at?->toDateTimeString(),
                'updated_at' => $order->shipment->updated_at?->toDateTimeString(),
                'estimate_date_shipped' => $order->shipment->estimate_date_shipped?->toDateTimeString(),
                'date_shipped' => $order->shipment->date_shipped?->toDateTimeString(),
                'customer_delivered_confirmed_at' => $order->shipment->customer_delivered_confirmed_at?->toDateTimeString(),
            ] : [],
            'address' => $order->address->toArray(),
            'products' => $order->products->toArray(),
            'digital_products' => $digitalProducts,
            'cancellation_reason' => $order->cancellation_reason_message,
        ], $order);
    }

    protected function getDigitalProductListView(Order $order): ?string
    {
        // Check if any digital products have downloadable files
        $hasDownloadableFiles = false;
        $hasLicenseCodesOnly = false;

        foreach ($order->products as $orderProduct) {
            if ($orderProduct->isTypeDigital()) {
                if ($orderProduct->hasFiles()) {
                    $hasDownloadableFiles = true;
                } elseif ($orderProduct->license_code && EcommerceHelperFacade::isEnabledLicenseCodesForDigitalProducts()) {
                    $hasLicenseCodesOnly = true;
                }
            }
        }

        // Use appropriate partial view based on product type
        if ($hasDownloadableFiles) {
            return view('plugins/ecommerce::emails.partials.digital-product-list', compact('order'))->render();
        } elseif ($hasLicenseCodesOnly) {
            return view('plugins/ecommerce::emails.partials.digital-product-license-codes', compact('order'))->render();
        }

        return null;
    }

    public function sendOrderConfirmationEmail(Order $order, bool $saveHistory = false, bool $force = false): bool
    {
        try {
            if (
                ! $force &&
                OrderHistory::query()
                    ->where([
                        'action' => OrderHistoryActionEnum::SEND_ORDER_CONFIRMATION_EMAIL,
                        'order_id' => $order->getKey(),
                    ])
                    ->exists()
            ) {
                return false;
            }

            if (
                is_plugin_active('payment')
                && ! in_array($order->payment->status, [PaymentStatusEnum::PENDING, PaymentStatusEnum::COMPLETED])
            ) {
                return false;
            }

            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            if ($mailer->templateEnabled('customer_new_order')) {
                $mailer = $this->setEmailVariables($order, $mailer);

                $mailer->sendUsingTemplate('customer_new_order', $order->user->email ?: $order->address->email);

                if ($saveHistory) {
                    OrderHistory::query()->create([
                        'action' => OrderHistoryActionEnum::SEND_ORDER_CONFIRMATION_EMAIL,
                        'description' => trans('plugins/ecommerce::order.confirmation_email_was_sent_to_customer'),
                        'order_id' => $order->getKey(),
                    ]);
                }
            }

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return false;
    }

    public function sendEmailForDigitalProducts(Order $order): void
    {
        if (! EcommerceHelperFacade::isEnabledSupportDigitalProducts()) {
            return;
        }

        $digitalProductsCount = EcommerceHelperFacade::countDigitalProducts($order->products);

        if ($digitalProductsCount) {
            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            $mailer = $this->setEmailVariables($order, $mailer);

            // Check if any digital products have downloadable files
            $hasDownloadableFiles = false;
            $hasLicenseCodesOnly = false;

            foreach ($order->products as $orderProduct) {
                if ($orderProduct->isTypeDigital()) {
                    if ($orderProduct->hasFiles()) {
                        $hasDownloadableFiles = true;
                    } elseif ($orderProduct->license_code && EcommerceHelperFacade::isEnabledLicenseCodesForDigitalProducts()) {
                        $hasLicenseCodesOnly = true;
                    }
                }
            }

            // Send appropriate email template based on product type
            if ($hasDownloadableFiles) {
                // Send download email for products with files
                $mailer->sendUsingTemplate('download_digital_products', $order->user->email ?: $order->address->email);
            } elseif ($hasLicenseCodesOnly) {
                // Send license codes email for products without files
                $mailer->sendUsingTemplate('digital_product_license_codes', $order->user->email ?: $order->address->email);
            }
        }
    }

    public function setOrderCompleted(int|string $orderId, Request $request, int|string $userId = 0): Order
    {
        /**
         * @var Order $order
         */
        $order = Order::query()->findOrFail($orderId);

        $order->status = OrderStatusEnum::COMPLETED;
        $order->completed_at = Carbon::now();
        $order->save();

        event(new OrderCompletedEvent($order));

        do_action(ACTION_AFTER_ORDER_STATUS_COMPLETED_ECOMMERCE, $order, $request);

        OrderHistory::query()->create([
            'action' => OrderHistoryActionEnum::MARK_ORDER_AS_COMPLETED,
            'description' => trans('plugins/ecommerce::order.mark_as_completed.history', [
                'admin' => Auth::check() ? Auth::user()->name : 'system',
                'time' => $order->completed_at,
            ]),
            'order_id' => $orderId,
            'user_id' => $userId,
        ]);

        return $order;
    }

    /**
     * @deprecated
     */
    public function makeInvoicePDF(Order $order): Pdf
    {
        return InvoiceHelperFacade::makeInvoicePDF($order->invoice);
    }

    /**
     * @deprecated
     */
    public function generateInvoice(Order $order): string
    {
        return InvoiceHelperFacade::generateInvoice($order->invoice);
    }

    /**
     * @deprecated
     */
    public function downloadInvoice(Order $order): Response
    {
        return InvoiceHelperFacade::downloadInvoice($order->invoice);
    }

    /**
     * @deprecated
     */
    public function streamInvoice(Order $order): Response
    {
        return InvoiceHelperFacade::streamInvoice($order->invoice);
    }

    public function getShippingMethod(string $method, array|string|null $option = null): array|string|null
    {
        $name = null;

        if ($method == ShippingMethodEnum::DEFAULT) {
            if ($option) {
                $rule = ShippingRule::query()->find($option);
                $name = $rule?->name;
            }

            if (empty($name)) {
                $name = trans('plugins/ecommerce::order.default');
            }
        }

        if (! $name && ShippingMethodEnum::search($method)) {
            $name = ShippingMethodEnum::getLabel($method);
        }

        return $name ?: $method;
    }

    public function processHistoryVariables(OrderHistory|ShipmentHistory $history): ?string
    {
        $variables = [
            'order_id' => Html::link(
                route('orders.edit', $history->order->id),
                $history->order->code . ' ' . BaseHelper::renderIcon('ti ti-external-link'),
                ['target' => '_blank'],
                null,
                false
            )
                ->toHtml(),
            'user_name' => $history->user_id === 0 ? trans('plugins/ecommerce::order.system') :
                BaseHelper::clean(
                    $history->user ? $history->user->name : (
                        $history->order->user->name ?:
                        $history->order->address->name
                    )
                ),
        ];

        $content = $history->description;

        foreach ($variables as $key => $value) {
            $content = str_replace('% ' . $key . ' %', $value, $content);
            $content = str_replace('%' . $key . '%', $value, $content);
            $content = str_replace('% ' . $key . '%', $value, $content);
            $content = str_replace('%' . $key . ' %', $value, $content);
        }

        return $content;
    }

    public function setOrderSessionData(?string $token, string|array $data): array
    {
        if (! $token) {
            $token = $this->getOrderSessionToken();
        }

        $data = array_replace_recursive($this->getOrderSessionData($token), $data);

        $data = $this->cleanData($data);

        session([md5('checkout_address_information_' . $token) => $data]);

        return $data;
    }

    public function getOrderSessionToken(): string
    {
        if (session()->has('tracked_start_checkout')) {
            $token = session('tracked_start_checkout');
        } else {
            $token = md5(Str::random(40));
            session(['tracked_start_checkout' => $token]);
        }

        return $token;
    }

    public function getOrderSessionData(?string $token = null): array
    {
        if (! $token) {
            $token = $this->getOrderSessionToken();
        }

        $data = [];
        $sessionKey = md5('checkout_address_information_' . $token);
        if (session()->has($sessionKey)) {
            $data = session($sessionKey);
        }

        return $this->cleanData($data);
    }

    public function cleanData(array $data): array
    {
        foreach ($data as $key => $item) {
            if (! is_string($item)) {
                continue;
            }

            $data[$key] = BaseHelper::clean($item);
        }

        return $data;
    }

    public function mergeOrderSessionData(?string $token, string|array $data): array
    {
        if (! $token) {
            $token = $this->getOrderSessionToken();
        }

        $data = array_merge($this->getOrderSessionData($token), $data);

        session([md5('checkout_address_information_' . $token) => $data]);

        return $this->cleanData($data);
    }

    public function clearSessions(?string $token): void
    {
        Cart::instance('cart')->destroy();
        session()->forget('applied_coupon_code');
        session()->forget('order_id');
        session()->forget(md5('checkout_address_information_' . $token));
        session()->forget('tracked_start_checkout');
    }

    public function handleAddCart(Product $product, Request $request, bool $relativePath = true): array
    {
        if ($product->status != BaseStatusEnum::PUBLISHED) {
            throw new ProductIsNotActivatedYetException();
        }

        $parentProduct = $product->original_product;

        $options = [];
        if ($requestOption = $request->input('options')) {
            $options = $this->getProductOptionData($requestOption);
        }

        $taxClasses = DB::table('ec_tax_products')
            ->join('ec_taxes', 'ec_taxes.id', '=', 'ec_tax_products.tax_id')
            ->where('ec_tax_products.product_id', $parentProduct->id)
            ->select(['ec_taxes.id', 'ec_taxes.title', 'ec_taxes.percentage'])
            ->get()
            ->mapWithKeys(function ($tax) {
                return [$tax->title => $tax->percentage];
            })
            ->all();

        $taxRate = $parentProduct->total_taxes_percentage;

        if (! $taxClasses && $defaultTaxRate = get_ecommerce_setting('default_tax_rate')) {
            $tax = cache()->remember('default_tax_rate_' . $defaultTaxRate, 3600, function () use ($defaultTaxRate) {
                return Tax::query()->where('id', $defaultTaxRate)->first();
            });

            if ($tax) {
                $taxClasses = [$tax->title => $tax->percentage];
                $taxRate = $tax->percentage;
            }
        }

        $image = $product->image ?: $parentProduct->image;

        if (! $relativePath) {
            $image = RvMedia::getImageUrl($image);
        }

        /**
         * Add cart to session
         */
        Cart::instance('cart')->add(
            $product->getKey(),
            BaseHelper::clean($parentProduct->name ?: $product->name),
            $request->input('qty', 1),
            $product->price()->getPrice(false),
            [
                'image' => $image,
                'attributes' => $product->is_variation ? $product->variation_attributes : '',
                'taxRate' => $taxRate,
                'taxClasses' => $taxClasses,
                'options' => $options,
                'extras' => $request->input('extras', []),
                'sku' => $product->sku,
                'weight' => $product->weight,
            ]
        );

        return Cart::instance('cart')->content()->toArray();
    }

    public function getProductOptionData(array $data, int|string|null $productId = null): array
    {
        $result = [
            'optionCartValue' => [],
        ];

        $data = array_filter($data);

        if (empty($data)) {
            return $result;
        }

        foreach ($data as $key => $option) {
            if (empty($option) || ! is_array($option) || ! isset($option['values'])) {
                continue;
            }

            $optionValue = OptionValue::query()
                ->select(['option_value', 'affect_price', 'affect_type'])
                ->where('option_id', $key);

            if ($option['option_type'] != 'field') {
                if (is_array($option['values'])) {
                    $optionValue->whereIn('option_value', $option['values']);
                } else {
                    $optionValue->whereIn('option_value', [0 => $option['values']]);
                }
            }

            $result['optionCartValue'][$key] = $optionValue->get()->toArray();

            foreach ($result['optionCartValue'][$key] as &$item) {
                $item['option_type'] = $option['option_type'];
            }

            if (
                $option['option_type'] == 'field' &&
                count($result['optionCartValue']) > 0
            ) {
                $result['optionCartValue'][$key][0]['option_value'] = $option['values'];
            }
        }

        $result['optionInfo'] = Option::query()
            ->whereIn('id', array_keys($data))
            ->pluck('name', 'id')
            ->when($productId, function ($query) use ($productId): void {
                $query->where('product_id', $productId);
            })
            ->all();

        return $result;
    }

    public function processAddressOrder(int|string $currentUserId, array $sessionData, Request $request): array
    {
        $address = null;

        $sessionAddressId = Arr::get($sessionData, 'address_id');
        if ($currentUserId && ! $sessionAddressId) {
            $address = Address::query()
                ->where([
                    'customer_id' => $currentUserId,
                    'is_default' => true,
                ])
                ->first();

            if ($address) {
                $sessionData['address_id'] = $address->id;
            }
        } elseif ($request->input('address.address_id') && $request->input('address.address_id') !== 'new') {
            $address = Address::query()->find($request->input('address.address_id'));
            if (! empty($address)) {
                $sessionData['address_id'] = $address->getKey();
            }
        }

        if ($sessionAddressId && $sessionAddressId !== 'new') {
            $address = Address::query()->find($sessionAddressId);
        }

        if (! empty($address)) {
            $addressData = [
                'name' => $address->name,
                'phone' => $address->phone,
                'email' => $address->email,
                'country' => $address->country,
                'state' => $address->state,
                'city' => $address->city,
                'address' => $address->address,
                'zip_code' => $address->zip_code,
                'order_id' => Arr::get($sessionData, 'created_order_id', 0),
            ];
        } elseif ((array) $request->input('address', [])) {
            $addressData = array_merge(
                ['order_id' => Arr::get($sessionData, 'created_order_id', 0)],
                (array) $request->input('address', [])
            );
        } else {
            $addressData = [
                'name' => Arr::get($sessionData, 'name'),
                'phone' => Arr::get($sessionData, 'phone'),
                'email' => Arr::get($sessionData, 'email'),
                'country' => Arr::get($sessionData, 'country'),
                'state' => Arr::get($sessionData, 'state'),
                'city' => Arr::get($sessionData, 'city'),
                'address' => Arr::get($sessionData, 'address'),
                'zip_code' => Arr::get($sessionData, 'zip_code'),
                'order_id' => Arr::get($sessionData, 'created_order_id', 0),
            ];
        }

        return $this->checkAndCreateOrderAddress($addressData, $sessionData);
    }

    public function checkAndCreateOrderAddress(array $addressData, array $sessionData): array
    {
        $addressData = $this->cleanData($addressData);

        $this->storeOrderBillingAddress($addressData, $sessionData);

        if (! Arr::get($sessionData, 'is_save_order_shipping_address', true)) {
            if ($createdOrderId = Arr::get($sessionData, 'created_order_id')) {
                OrderAddress::query()
                    ->where([
                        'order_id' => $createdOrderId,
                        'type' => OrderAddressTypeEnum::SHIPPING,
                    ])
                    ->delete();
                Arr::forget($sessionData, 'created_order_address');
                Arr::forget($sessionData, 'created_order_address_id');
            }
        } elseif ($addressData && ! empty($addressData['name'])) {
            if (! isset($sessionData['created_order_address'])) {
                $createdOrderAddress = $this->createOrderAddress($addressData, $sessionData);
                if ($createdOrderAddress) {
                    $sessionData['created_order_address'] = true;
                    $sessionData['created_order_address_id'] = $createdOrderAddress->getKey();
                }
            } elseif (Arr::get($sessionData, 'created_order_address_id')) {
                $createdOrderAddress = $this->createOrderAddress($addressData, $sessionData);
                $sessionData['created_order_address'] = true;
                $sessionData['created_order_address_id'] = $createdOrderAddress->getKey();
            }
        }

        return $sessionData;
    }

    protected function storeOrderBillingAddress(array $data, array $sessionData = []): void
    {
        if (! EcommerceHelperFacade::isBillingAddressEnabled()) {
            return;
        }

        $orderId = Arr::get($data, 'order_id', Arr::get($data, 'created_order_id'));
        if ($orderId) {
            $billingAddressSameAsShippingAddress = Arr::get(
                $sessionData,
                'billing_address_same_as_shipping_address',
                '1'
            );

            if (
                ! $billingAddressSameAsShippingAddress ||
                ! Arr::get($sessionData, 'is_save_order_shipping_address', true)
            ) {
                $addressData = Arr::only(
                    $sessionData,
                    ['name', 'phone', 'email', 'country', 'state', 'city', 'address', 'zip_code']
                );

                if ($billingAddressSameAsShippingAddress) {
                    $billingAddressData = $addressData;
                } else {
                    $billingAddressData = Arr::get($sessionData, 'billing_address', []);
                }

                $rules = EcommerceHelperFacade::getCustomerAddressValidationRules();
                $validator = Validator::make($billingAddressData, $rules);
                if ($validator->fails()) {
                    return;
                }

                $billingAddressData['order_id'] = $orderId;
                $billingAddressData['type'] = OrderAddressTypeEnum::BILLING;

                $orderBillingAddress = OrderAddress::query()
                    ->firstOrNew([
                        'order_id' => $orderId,
                        'type' => OrderAddressTypeEnum::BILLING,
                    ]);

                $orderBillingAddress->fill($billingAddressData);
                $orderBillingAddress->save();
            } else {
                OrderAddress::query()
                    ->where([
                        'order_id' => $orderId,
                        'type' => OrderAddressTypeEnum::BILLING,
                    ])
                    ->delete();
            }
        }
    }

    protected function createOrderAddress(array $data, ?array $sessionData = []): OrderAddress|bool
    {
        $data['type'] = OrderAddressTypeEnum::SHIPPING;

        if ($orderId = Arr::get($sessionData, 'created_order_id')) {
            /**
             * @var OrderAddress $orderAddress
             */
            $orderAddress = OrderAddress::query()
                ->where([
                    'order_id' => $orderId,
                    'type' => OrderAddressTypeEnum::SHIPPING,
                ])
                ->firstOrNew();

            $orderAddress->fill($data);

            $orderAddress->save();

            return $orderAddress;
        }

        $rules = EcommerceHelperFacade::getCustomerAddressValidationRules();

        $products = Cart::instance('cart')->products();

        $countDigitalProducts = EcommerceHelperFacade::countDigitalProducts($products);
        if (! auth('customer')->check() && $countDigitalProducts) {
            $rules['email'] = 'required|max:60|min:6';
            if ($countDigitalProducts == $products->count()) {
                $keys = [
                    'country',
                    'state',
                    'city',
                    'address',
                    'phone',
                    'zip_code',
                ];
                $rules = (new CheckoutRequest())->removeRequired($rules, $keys);
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return false;
        }

        /**
         * @var OrderAddress $orderAddress
         */
        $orderAddress = OrderAddress::query()->create($data);

        return $orderAddress;
    }

    public function processOrderProductData(array|Collection $products, array $sessionData): array
    {
        $createdOrderProduct = Arr::get($sessionData, 'created_order_product');

        $cartItems = $products['products']->pluck('cartItem');

        $lastUpdatedAt = Cart::instance('cart')->getLastUpdatedAt();

        // Check latest updated at of cart
        if (! $createdOrderProduct || ! $createdOrderProduct->eq($lastUpdatedAt)) {
            $orderProducts = OrderProduct::query()
                ->where('order_id', $sessionData['created_order_id'])
                ->get();
            $productIds = [];

            foreach ($cartItems as $cartItem) {
                $productByCartItem = $products['products']->firstWhere('id', $cartItem->id);

                $data = [
                    'order_id' => $sessionData['created_order_id'],
                    'product_id' => $cartItem->id,
                    'product_name' => $cartItem->name,
                    'product_image' => $cartItem->options['image'],
                    'qty' => $cartItem->qty,
                    'weight' => $productByCartItem->weight * $cartItem->qty,
                    'price' => EcommerceHelper::roundPrice($cartItem->price),
                    'tax_amount' => EcommerceHelper::roundPrice($cartItem->tax * $cartItem->qty),
                    'options' => [],
                    'product_type' => $productByCartItem->product_type,
                ];

                if ($cartItem->options) {
                    $data['options'] = $cartItem->options;
                }

                if (isset($cartItem->options['options'])) {
                    $data['product_options'] = $cartItem->options['options'];
                }

                $orderProduct = $orderProducts->firstWhere('product_id', $cartItem->id);

                if ($orderProduct) {
                    $orderProduct->fill($data);
                    $orderProduct->save();
                } else {
                    /**
                     * @var OrderProduct $orderProduct
                     */
                    $orderProduct = OrderProduct::query()->create($data);

                    OrderProductCreatedEvent::dispatch($orderProduct);
                    do_action('ecommerce_after_each_order_product_created', $orderProduct);
                }

                $productIds[] = $cartItem->id;
            }

            // Delete orderProducts not exists;
            foreach ($orderProducts as $orderProduct) {
                if (! in_array($orderProduct->product_id, $productIds)) {
                    $orderProduct->delete();
                }
            }

            $sessionData['created_order_product'] = $lastUpdatedAt;
        }

        return $sessionData;
    }

    /**
     * @param       $sessionData
     * @param       $request
     * @param       $cartItems
     * @param       $order
     * @param array $generalData
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function processOrderInCheckout(
        $sessionData,
        $request,
        $cartItems,
        $order,
        array $generalData
    ): array {
        $createdOrder = Arr::get($sessionData, 'created_order');
        $createdOrderId = Arr::get($sessionData, 'created_order_id');

        $lastUpdatedAt = Cart::instance('cart')->getLastUpdatedAt();

        // Get payment fee if applicable
        $paymentFee = 0;
        $paymentMethod = $request->input('payment_method');
        if ($paymentMethod && is_plugin_active('payment')) {
            $orderAmount = Cart::instance('cart')->rawTotalByItems($cartItems);
            $paymentFee = PaymentFeeHelper::calculateFee($paymentMethod, $orderAmount);
        }

        // Calculate total amount including payment fee
        $amount = Cart::instance('cart')->rawTotalByItems($cartItems) + $paymentFee;

        $data = array_merge([
            'amount' => $amount,
            'shipping_method' => $request->input('shipping_method', ShippingMethodEnum::DEFAULT),
            'shipping_option' => $request->input('shipping_option'),
            'payment_fee' => $paymentFee,
            'tax_amount' => Cart::instance('cart')->rawTaxByItems($cartItems),
            'sub_total' => Cart::instance('cart')->rawSubTotalByItems($cartItems),
            'coupon_code' => session()->get('applied_coupon_code'),
        ], $generalData);

        if ($createdOrder && $createdOrderId) {
            if ($order && (is_string($createdOrder) || ! $createdOrder->eq($lastUpdatedAt))) {
                $order->fill($data);
            }
        }

        if (! $order) {
            $data = array_merge($data, [
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'status' => OrderStatusEnum::PENDING,
                'is_finished' => false,
            ]);

            $order = Order::query()->create($data);
        }

        $sessionData['created_order'] = $lastUpdatedAt; // insert last updated at in here
        $sessionData['created_order_id'] = $order->id;

        return [$sessionData, $order];
    }

    public function createOrder(Request $request, int|string $currentUserId, string $token, array $cartItems)
    {
        // Get payment fee if applicable
        $paymentFee = 0;
        $paymentMethod = $request->input('payment_method');
        if ($paymentMethod && is_plugin_active('payment')) {
            $orderAmount = Cart::instance('cart')->rawTotalByItems($cartItems);
            $paymentFee = PaymentFeeHelper::calculateFee($paymentMethod, $orderAmount);
        }

        // Calculate total amount including payment fee
        $amount = Cart::instance('cart')->rawTotalByItems($cartItems) + $paymentFee;

        $request->merge([
            'amount' => $amount,
            'user_id' => $currentUserId,
            'shipping_method' => $request->input('shipping_method', ShippingMethodEnum::DEFAULT),
            'shipping_option' => $request->input('shipping_option'),
            'shipping_amount' => 0,
            'payment_fee' => $paymentFee,
            'tax_amount' => Cart::instance('cart')->rawTaxByItems($cartItems),
            'sub_total' => Cart::instance('cart')->rawSubTotalByItems($cartItems),
            'coupon_code' => session()->get('applied_coupon_code'),
            'discount_amount' => 0,
            'status' => OrderStatusEnum::PENDING,
            'is_finished' => false,
            'token' => $token,
        ]);

        return Order::query()->create($request->input());
    }

    public function confirmPayment(Order $order, ?User $user = null): bool
    {
        if (! is_plugin_active('payment')) {
            return false;
        }

        $payment = $order->payment;

        if (! $payment) {
            return false;
        }

        $user = $user ?? auth()->user();

        $payment->status = PaymentStatusEnum::COMPLETED;
        $payment->amount = $payment->amount ?: 0;
        $payment->user_id = $user?->getKey() ?: 0;
        $payment->save();

        event(new OrderPaymentConfirmedEvent($order, $user));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('order_confirm_payment')) {
            $mailer = $this->setEmailVariables($order, $mailer);
            $mailer->sendUsingTemplate(
                'order_confirm_payment',
                $order->user->email ?: $order->address->email
            );
        }

        OrderHistory::query()->create([
            'action' => OrderHistoryActionEnum::CONFIRM_PAYMENT,
            'description' => trans('plugins/ecommerce::order.payment_was_confirmed_by', [
                'money' => format_price($order->amount),
            ]),
            'order_id' => $order->getKey(),
            'user_id' => $user?->getKey(),
        ]);

        if (EcommerceHelperFacade::isEnabledSupportDigitalProducts()) {
            $digitalProductsCount = EcommerceHelperFacade::countDigitalProducts($order->products);

            if (
                $digitalProductsCount === $order->products->count()
                && EcommerceHelperFacade::isAutoCompleteDigitalOrdersAfterPayment()
            ) {
                $this->setOrderCompleted($order->getKey(), request(), $user?->getKey() ?? 0);
            } elseif ($digitalProductsCount > 0) {
                // Fire OrderCompletedEvent for digital products even if order is not auto-completed
                event(new OrderCompletedEvent($order));
            }
        }

        return true;
    }

    public function cancelOrder(Order $order, ?string $reason = null, ?string $reasonDescription = null): Order
    {
        $order->status = OrderStatusEnum::CANCELED;
        $order->is_confirmed = true;

        if ($reason) {
            $order->cancellation_reason = $reason;
            $order->cancellation_reason_description = $reasonDescription;
        }

        $order->save();

        if (
            is_plugin_active('payment')
            && $order->payment_id
            && $order->payment->id
            && $order->payment->status == PaymentStatusEnum::PENDING
        ) {
            $payment = $order->payment;
            $payment->status = PaymentStatusEnum::CANCELED;
            $payment->save();
        }

        event(new OrderCancelledEvent($order, $reason, $reasonDescription));

        foreach ($order->products as $orderProduct) {
            $product = $orderProduct->product;

            if (! $product) {
                continue;
            }

            // Only restore quantity if storehouse management is enabled
            if ($product->with_storehouse_management) {
                $product->quantity += $orderProduct->qty;
                $product->save();

                // Trigger event to update parent product if this is a variation
                event(new ProductQuantityUpdatedEvent($product));
            }
        }

        if ($order->coupon_code && $order->user_id) {
            Discount::getFacadeRoot()->afterOrderCancelled($order->coupon_code, $order->user_id);
        }

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('customer_cancel_order')) {
            $mailer = $this->setEmailVariables($order, $mailer);
            $mailer->sendUsingTemplate(
                'customer_cancel_order',
                $order->user->email ?: $order->address->email
            );
        }

        if ($mailer->templateEnabled('order_cancellation_to_admin')) {
            $mailer = $this->setEmailVariables($order, $mailer);
            $mailer->sendUsingTemplate('order_cancellation_to_admin');
        }

        if (AdminHelper::isInAdmin() && Auth::check() && $mailer->templateEnabled('admin_cancel_order')) {
            $mailer = $this->setEmailVariables($order, $mailer);
            $mailer->sendUsingTemplate(
                'admin_cancel_order',
                $order->user->email ?: $order->address->email
            );
        }

        return $order;
    }

    public function shippingStatusDelivered(Shipment $shipment, Request $request, int|string $userId = 0): Order
    {
        return $this->setOrderCompleted($shipment->order_id, $request, $userId);
    }

    public function getOrderBankInfo(Order|EloquentCollection $orders): ?string
    {
        if (! is_plugin_active('payment')) {
            return null;
        }

        try {
            if (! $orders instanceof EloquentCollection) {
                $collection = new EloquentCollection();
                $collection->add($orders);
                $orders = $collection;
            }

            $orders = $orders->filter(function ($item) {
                return $item->payment->payment_channel == PaymentMethodEnum::BANK_TRANSFER &&
                    $item->payment->status == PaymentStatusEnum::PENDING;
            });

            if ($orders->isEmpty()) {
                return null;
            }

            $bankInfo = get_payment_setting('description', $orders->first()->payment->payment_channel);

            $orderAmount = 0;
            $orderCode = '';

            if ($bankInfo) {
                foreach ($orders as $item) {
                    $orderAmount += $item->amount;
                    $orderCode .= $item->code . ', ';
                }

                $orderCode = rtrim(trim($orderCode), ',');
            }

            $bankInfo = view(
                'plugins/ecommerce::orders.partials.bank-transfer-info',
                compact('bankInfo', 'orderAmount', 'orderCode', 'orders')
            )->render();

            return apply_filters('ecommerce_order_bank_info', $bankInfo, $orders);
        } catch (Throwable) {
            return null;
        }
    }

    public function confirmOrder(Order $order): void
    {
        if ($order->is_confirmed) {
            return;
        }

        $order->is_confirmed = 1;
        if ($order->status == OrderStatusEnum::PENDING) {
            $order->status = OrderStatusEnum::PROCESSING;
        }

        $order->save();

        if (is_plugin_active('payment')) {
            $payment = Payment::query()->where('order_id', $order->getKey())->first();

            if ($payment && Auth::check()) {
                $payment->user_id = Auth::id();
                $payment->save();
            }
        }

        event(
            new OrderConfirmedEvent($order, EcommerceHelperFacade::isOrderAutoConfirmedEnabled() ? null : Auth::user())
        );

        OrderHistory::query()->create([
            'action' => OrderHistoryActionEnum::CONFIRM_ORDER,
            'description' => trans('plugins/ecommerce::order.order_was_verified_by'),
            'order_id' => $order->getKey(),
            'user_id' => Auth::id() ?: 0,
        ]);

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('order_confirm')) {
            $mailer = $this->setEmailVariables($order, $mailer);
            $mailer->sendUsingTemplate('order_confirm', $order->user->email ?: $order->address->email);
        }
    }

    public function createOrUpdateIncompleteOrder(array $data, ?Order $order = null): Order|null|false
    {
        $data['is_finished'] = false;

        if ($order) {
            $order->fill($data);
            $order->save();
        } else {
            $order = Order::query()->create($data);
        }

        /**
         * @var Order $order
         */

        $this->captureFootprints($order);

        do_action('ecommerce_create_order_from_data', $data, $order);

        return $order;
    }

    public function captureFootprints(Order $order): void
    {
        if ($order->referral()->exists()) {
            return;
        }

        $referrals = app(FootprinterInterface::class)->getFootprints();

        if ($referrals) {
            try {
                $order->referral()->create($referrals);
            } catch (Throwable) {
                $referrals = array_map(function (?string $item) {
                    return is_string($item) ? substr($item, 0, 190) : $item;
                }, $referrals);

                rescue(function () use ($order, $referrals): void {
                    $order->referral()->create($referrals);
                }, report: false);
            }
        }
    }
}
