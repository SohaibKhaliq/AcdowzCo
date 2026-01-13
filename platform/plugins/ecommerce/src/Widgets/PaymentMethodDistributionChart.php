<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentMethodDistributionChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'donut';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.payment_method_distribution');
    }

    public function getOptions(): array
    {
        if (! is_plugin_active('payment')) {
            return [
                'series' => [],
                'chart' => [
                    'height' => 350,
                    'type' => 'donut',
                ],
                'labels' => [],
            ];
        }

        $payments = Payment::query()
            ->whereIn('status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->select([
                'payment_channel',
                DB::raw('count(*) as total'),
                DB::raw('sum(amount) as amount'),
            ])
            ->groupBy('payment_channel')
            ->get();

        $series = [];
        $labels = [];
        $colors = ['#4ade80', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#ec4899'];

        foreach ($payments as $payment) {
            $series[] = $payment->total;

            $paymentMethod = PaymentMethodEnum::getLabel($payment->payment_channel);
            $labels[] = $paymentMethod . ' (' . format_price($payment->amount) . ')';
        }

        return [
            'series' => $series,
            'chart' => [
                'height' => 350,
                'type' => 'donut',
            ],
            'colors' => $colors,
            'labels' => $labels,
            'legend' => [
                'position' => 'bottom',
            ],
        ];
    }
}
