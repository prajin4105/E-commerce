<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_cart_value',
        'max_uses',
        'used_count',
        'per_user_limit',
        'valid_from',
        'valid_to',
        'is_active',
        'show_to_user',
        'user_id',
        'product_ids',
        'category_ids',
        'free_shipping',
        'exclusive',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_cart_value' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'is_active' => 'boolean',
        'show_to_user' => 'boolean',
        'product_ids' => 'array',
        'category_ids' => 'array',
        'free_shipping' => 'boolean',
        'exclusive' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(CouponUsageLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_to')
                    ->orWhere('valid_to', '>=', now());
            });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('max_uses')
                    ->orWhereRaw('used_count < max_uses');
            });
    }

    public function isExpired(): bool
    {
        return $this->valid_to && $this->valid_to->isPast();
    }

    public function isNotStarted(): bool
    {
        return $this->valid_from && $this->valid_from->isFuture();
    }

    public function isUsageLimitReached(): bool
    {
        return $this->max_uses && $this->used_count >= $this->max_uses;
    }

    public function canBeUsedByUser(User $user): bool
    {
        // Check if coupon is user-specific
        if ($this->user_id && $this->user_id !== $user->id) {
            return false;
        }

        // Check per user limit
        $userUsageCount = $this->usageLogs()
            ->where('user_id', $user->id)
            ->count();

        return $userUsageCount < $this->per_user_limit;
    }

    public function calculateDiscount(float $cartTotal): float
    {
        if ($this->discount_type === 'fixed') {
            return min($this->discount_value, $cartTotal);
        }

        // Percentage discount
        return ($cartTotal * $this->discount_value) / 100;
    }
}
