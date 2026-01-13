<?php

namespace Botble\Payment\Models;

use Botble\ACL\Models\User;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

class Payment extends BaseModel
{
    protected $table = 'payments';

    protected static function booted(): void
    {
        static::deleting(function (Payment $payment) {
            if ($payment->charge_id) {
                $logQuery = PaymentLog::query()
                    ->where('payment_method', $payment->payment_channel)
                    ->where(function ($query) use ($payment) {
                        $query->where('request', 'LIKE', '%' . $payment->charge_id . '%')
                            ->orWhere('response', 'LIKE', '%' . $payment->charge_id . '%');

                        if ($payment->order_id) {
                            $query->orWhere('request', 'LIKE', '%' . $payment->order_id . '%')
                                ->orWhere('response', 'LIKE', '%' . $payment->order_id . '%');
                        }
                    });

                $deletedCount = $logQuery->delete();

                if ($deletedCount > 0) {
                    Log::info("Deleted {$deletedCount} payment logs for payment", [
                        'payment_id' => $payment->id,
                        'charge_id' => $payment->charge_id,
                        'payment_method' => $payment->payment_channel->value ?? 'unknown',
                    ]);
                }
            }
        });
    }

    protected $fillable = [
        'amount',
        'payment_fee',
        'currency',
        'user_id',
        'charge_id',
        'payment_channel',
        'description',
        'status',
        'order_id',
        'payment_type',
        'customer_id',
        'customer_type',
        'refunded_amount',
        'refund_note',
    ];

    protected $casts = [
        'payment_channel' => PaymentMethodEnum::class,
        'status' => PaymentStatusEnum::class,
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function customer(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function getDescription(): string
    {
        $time = Html::tag('span', $this->created_at->diffForHumans(), ['class' => 'small italic']);

        return __('You have created a payment #:charge_id via :channel :time : :amount', [
            'charge_id' => $this->charge_id,
            'channel' => $this->payment_channel->label(),
            'time' => $time,
            'amount' => number_format($this->amount, 2) . $this->currency,
        ]);
    }

    public function getPaymentLogs()
    {
        if (! $this->charge_id) {
            return collect();
        }

        return PaymentLog::query()
            ->where('payment_method', $this->payment_channel)
            ->where(function ($query) {
                $query->where('request', 'LIKE', '%' . $this->charge_id . '%')
                    ->orWhere('response', 'LIKE', '%' . $this->charge_id . '%');

                if ($this->order_id) {
                    $query->orWhere('request', 'LIKE', '%' . $this->order_id . '%')
                        ->orWhere('response', 'LIKE', '%' . $this->order_id . '%');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
