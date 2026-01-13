<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbandonedCart extends BaseModel
{
    protected $table = 'ec_abandoned_carts';

    protected $fillable = [
        'customer_id',
        'session_id',
        'cart_data',
        'total_amount',
        'items_count',
        'email',
        'phone',
        'customer_name',
        'abandoned_at',
        'reminder_sent_at',
        'reminders_sent',
        'is_recovered',
        'recovered_at',
        'recovered_order_id',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'total_amount' => 'decimal:2',
        'items_count' => 'integer',
        'abandoned_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'reminders_sent' => 'integer',
        'is_recovered' => 'boolean',
        'recovered_at' => 'datetime',
        'customer_name' => SafeContent::class,
        'email' => SafeContent::class,
        'phone' => SafeContent::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->customer_id && ! Customer::query()->where('id', $model->customer_id)->exists()) {
                $model->customer_id = null;
            }

            if ($model->recovered_order_id && ! Order::query()->where('id', $model->recovered_order_id)->exists()) {
                $model->recovered_order_id = null;
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function recoveredOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'recovered_order_id');
    }

    public function scopeAbandoned($query)
    {
        return $query->where('is_recovered', false)
            ->whereNotNull('abandoned_at');
    }

    public function scopeNotReminded($query)
    {
        return $query->whereNull('reminder_sent_at');
    }

    public function scopeCanSendReminder($query, int $hoursAfterAbandonment = 1)
    {
        return $query->abandoned()
            ->where('abandoned_at', '<=', now()->subHours($hoursAfterAbandonment));
    }

    public function markAsRecovered(Order $order): void
    {
        $this->update([
            'is_recovered' => true,
            'recovered_at' => now(),
            'recovered_order_id' => $order->id,
        ]);
    }

    public function incrementRemindersSent(): void
    {
        $this->update([
            'reminder_sent_at' => now(),
            'reminders_sent' => $this->reminders_sent + 1,
        ]);
    }

    public function getCartItems(): array
    {
        $items = [];
        $cartData = $this->cart_data ?? [];

        foreach ($cartData as $item) {
            $product = Product::find($item['id'] ?? null);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['qty'] ?? 1,
                    'options' => $item['options'] ?? [],
                ];
            }
        }

        return $items;
    }
}
