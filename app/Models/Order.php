<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'phone_number',
        'product_id',
        'quantity',
        'total_price',
        'total_amount',
        'discount_amount',
        'final_amount',
        'coupon_id',
        'status',
        'payment_status',
        'shipping_address',
        'billing_address',
        'order_number',
    ];

    protected $attributes = [
        'quantity' => 1,
        'total_price' => 0.00,
        'total_amount' => 0.00,
        'discount_amount' => 0.00,
        'final_amount' => 0.00,
        'status' => 'pending',
        'payment_status' => 'pending',
        'billing_address' => null,
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Generate order number if not set
            if (empty($order->order_number)) {
                $lastOrder = static::orderBy('id', 'desc')->first();
                $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
                $order->order_number = 'ORD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
            
            // Set total_amount if not set
            if (empty($order->total_amount)) {
                $order->total_amount = $order->total_price ?? 0.00;
            }

            // Set billing_address to shipping_address if not set
            if (empty($order->billing_address) && !empty($order->shipping_address)) {
                $order->billing_address = $order->shipping_address;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function canBeCancelled()
    {
        // Allow cancellation only if status is 'placed'
        return $this->status === 'placed';
    }
}
