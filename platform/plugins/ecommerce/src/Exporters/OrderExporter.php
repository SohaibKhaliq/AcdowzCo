<?php

namespace Botble\Ecommerce\Exporters;

use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\ExportCounter;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderExporter extends Exporter
{
    protected ?int $limit = null;

    protected ?string $status = null;

    protected ?string $startDate = null;

    protected ?string $endDate = null;

    public function setLimit(?int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function setDateRange(?string $startDate, ?string $endDate): static
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        return $this;
    }

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::order.menu');
    }

    public function columns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('created_at')->label('Order Date'),
            ExportColumn::make('status'),
            ExportColumn::make('customer_name'),
            ExportColumn::make('customer_email'),
            ExportColumn::make('customer_phone'),
            ExportColumn::make('amount'),
            ExportColumn::make('discount_amount'),
            ExportColumn::make('tax_amount'),
            ExportColumn::make('shipping_amount')->label('Shipping Fee'),
            ExportColumn::make('sub_total'),
            ExportColumn::make('shipping_address_full_address')->label('Shipping Address'),
            ExportColumn::make('billing_address_full_address')->label('Billing Address'),
            ExportColumn::make('payment_channel'),
            ExportColumn::make('payment_status'),
            ExportColumn::make('payment_amount'),
            ExportColumn::make('payment_created_at')->label('Payment Date'),
            ExportColumn::make('shipping_method_name')->label('Shipping Method'),
            ExportColumn::make('shipment_status')->label('Shipping Status'),
            ExportColumn::make('shipment_date_shipped')->label('Shipping Date'),
            ExportColumn::make('shipment_tracking_id')->label('Tracking ID'),
            ExportColumn::make('shipment_shipping_company_name')->label('Shipping Company'),
            ExportColumn::make('products'),
        ];
    }

    protected function applyFilters(Builder $query): void
    {
        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->startDate));
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->endDate));
        }

        if ($this->limit) {
            $query->latest()->limit($this->limit);
        } else {
            $query->oldest();
        }
    }

    public function collection(): Collection
    {
        $query = Order::query()
            ->with([
                'shippingAddress',
                'billingAddress',
                'payment',
                'user',
                'products',
                'shipment',
            ]);

        $this->applyFilters($query);

        return $query->get();
    }

    public function counters(): array
    {
        $query = Order::query();

        $this->applyFilters($query);

        return [
            ExportCounter::make()
                ->label(trans('plugins/ecommerce::order.export.total_orders'))
                ->value($query->count()),
        ];
    }

    public function hasDataToExport(): bool
    {
        return Order::query()->exists();
    }

    /**
     * @param Order $row
     */
    public function map($row): array
    {
        $products = $row
            ->products
            ->map(fn (OrderProduct $product) => $product->product_name . (! empty($product->options['sku']) ? ' (' . $product->options['sku'] . ')' : null) . ' x ' . $product->qty)
            ->implode(', ');

        $data = [
            'id' => $row->getKey(),
            'created_at' => $row->created_at,
            'status' => $row->status,
            'customer_name' => $row->shippingAddress->name ?: $row->user->name,
            'customer_email' => $row->shippingAddress->email ?: $row->user->email,
            'customer_phone' => $row->shippingAddress->phone ?: $row->user->phone,
            'amount' => $row->amount,
            'discount_amount' => $row->discount_amount,
            'tax_amount' => $row->tax_amount,
            'shipping_amount' => $row->shipping_amount,
            'sub_total' => $row->sub_total,
            'shipping_address_full_address' => $row->shippingAddress->full_address ?: '-',
            'billing_address_full_address' => $row->billingAddress->full_address ?: '-',
            'payment_payment_channel' => $row->payment->payment_channel,
            'payment_status' => $row->payment->status,
            'payment_amount' => $row->payment->amount,
            'payment_created_at' => $row->payment->created_at,
            'shipping_method_name' => $row->shipping_method_name,
            'shipment_status' => $row->shipment->status,
            'shipment_date_shipped' => $row->shipment->date_shipped,
            'shipment_tracking_id' => $row->shipment->tracking_id,
            'shipment_shipping_company_name' => $row->shipment->shipping_company_name,
            'products' => $products,
        ];

        return parent::map($data);
    }

    protected function getView(): string
    {
        return 'plugins/ecommerce::orders.export';
    }
}
