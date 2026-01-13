<?php

namespace Botble\Ecommerce\Http\Controllers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Pdf;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Models\Shipment;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\Media\Facades\RvMedia;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PrintShippingLabelController extends BaseController
{
    public function __invoke(Shipment $shipment, Pdf $pdf): ?Response
    {
        $this->pageTitle(trans('plugins/ecommerce::shipping.shipping_label.print_shipping_label'));

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $url = $shipment->tracking_link;

        if (! $url) {
            $params = [
                'order_id' => get_order_code($shipment->order_id),
            ];

            $customer = $shipment->order->user;

            $orderAddress  = $shipment->order->address;

            if (EcommerceHelper::isOrderTrackingUsingPhone()) {
                $params['phone'] = $orderAddress->phone ?: $customer->phone;
            } else {
                $params['email'] = $orderAddress->email ?: $customer->email;
            }

            $url = route('public.orders.tracking', $params);
        }

        $qrCode = $writer->writeString($url);

        $country = EcommerceHelper::getCountryNameById(get_ecommerce_setting('store_country'));
        $state = get_ecommerce_setting('store_state');
        $city = get_ecommerce_setting('store_city');

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            if (is_numeric($state)) {
                $state = State::query()->where('id', $state)->value('name');
            }

            if (is_numeric($city)) {
                $city = City::query()->where('id', $city)->value('name');
            }
        }

        $address = get_ecommerce_setting('store_address');

        $zipCode = get_ecommerce_setting('store_zip_code');

        $fullAddress = implode(', ', array_filter([
            $address,
            $city,
            $state,
            $country,
            EcommerceHelper::isZipCodeEnabled() ? $zipCode : '',
        ]));

        $order = $shipment->order;

        return $pdf
            ->templatePath(plugin_path('ecommerce/resources/templates/shipping-label.tpl'))
            ->destinationPath(storage_path('app/templates/ecommerce/shipping-label.tpl'))
            ->paperSizeHalfLetter()
            ->supportLanguage(InvoiceHelper::getLanguageSupport())
            ->data(apply_filters('ecommerce_shipping_label_data', [
                'shipment' => [
                    'order_number' => get_order_code($shipment->order_id),
                    'code' => get_shipment_code($shipment->getKey()),
                    'weight' => $shipment->weight,
                    'weight_unit' => ecommerce_weight_unit(),
                    'created_at' => BaseHelper::formatDate($shipment->created_at),
                    'shipping_method' => $order->shipping_method_name,
                    'shipping_fee' => format_price($shipment->price),
                    'shipping_company_name' => $shipment->shipping_company_name,
                    'tracking_id' => $shipment->tracking_id,
                    'tracking_link' => $shipment->tracking_link,
                    'note' => Str::limit((string) $shipment->note, 90),
                    'qr_code' => base64_encode($qrCode),
                    'order' => [
                        'amount' => format_price($order->amount),
                        'tax_amount' => format_price($order->tax_amount),
                        'shipping_amount' => format_price($order->shipping_amount),
                        'discount_amount' => format_price($order->discount_amount),
                        'sub_total' => format_price($order->sub_total),
                    ],
                ],
                'sender' => [
                    'logo' => RvMedia::getRealPath(Theme::getLogo()),
                    'name' => get_ecommerce_setting('store_name'),
                    'phone' => get_ecommerce_setting('store_phone'),
                    'email' => get_ecommerce_setting('store_email'),
                    'country' => $country,
                    'state' => $state,
                    'city' => $city,
                    'zip_code' => $zipCode,
                    'address' => $address,
                    'full_address' => $fullAddress,
                ],
                'receiver' => [
                    'name' => $order->shippingAddress->name ?: $order->user->name,
                    'full_address' => $order->full_address,
                    'email' => $order->shippingAddress->email ?: $order->user->email,
                    'phone' => $order->shippingAddress->phone ?: $order->user->phone,
                    'note' => Str::limit((string) $order->description, 90),
                ],
            ], $shipment))
            ->setProcessingLibrary(get_ecommerce_setting('invoice_processing_library', 'dompdf'))
            ->stream('shipping-label.pdf');
    }
}
