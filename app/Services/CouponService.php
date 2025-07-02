<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsageLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function validateCoupon(string $code, User $user, float $cartTotal, Collection $cartItems = null): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Invalid coupon code.',
                'coupon' => null
            ];
        }

        if (!$coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'This coupon is not active.',
                'coupon' => $coupon
            ];
        }

        if ($coupon->isNotStarted()) {
            return [
                'valid' => false,
                'message' => 'This coupon is not yet active.',
                'coupon' => $coupon
            ];
        }

        if ($coupon->isExpired()) {
            return [
                'valid' => false,
                'message' => 'This coupon has expired.',
                'coupon' => $coupon
            ];
        }

        if ($coupon->isUsageLimitReached()) {
            return [
                'valid' => false,
                'message' => 'This coupon has reached its usage limit.',
                'coupon' => $coupon
            ];
        }

        if (!$coupon->canBeUsedByUser($user)) {
            return [
                'valid' => false,
                'message' => 'You have already used this coupon the maximum number of times.',
                'coupon' => $coupon
            ];
        }

        $minCartValue = (float) $coupon->minimum_cart_value;
        if ($cartTotal < $minCartValue) {
            return [
                'valid' => false,
                'message' => "Minimum cart value of ₹{$minCartValue} required for this coupon.",
                'coupon' => $coupon
            ];
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'coupon' => $coupon
        ];
    }

    public function applyCoupon(Coupon $coupon, float $cartTotal): array
    {
        $discountAmount = $coupon->calculateDiscount($cartTotal);
        $newTotal = $cartTotal - $discountAmount;

        return [
            'coupon_code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'discount_amount' => $discountAmount,
            'original_total' => $cartTotal,
            'new_total' => $newTotal,
            'free_shipping' => $coupon->free_shipping,
        ];
    }

    public function recordCouponUsage(Coupon $coupon, User $user, Order $order, float $discountAmount): void
    {
        DB::transaction(function () use ($coupon, $user, $order, $discountAmount) {
            // Increment coupon usage count
            $coupon->increment('used_count');

            // Create usage log
            CouponUsageLog::create([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'order_id' => $order->id,
                'discount_amount' => $discountAmount,
                'used_at' => now(),
            ]);
        });
    }

    public function getCouponStats(): array
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::active()->count();
        $expiredCoupons = Coupon::where('valid_to', '<', now())->count();

        $mostUsedCoupons = Coupon::orderBy('used_count', 'desc')
            ->limit(5)
            ->get(['code', 'used_count', 'discount_type', 'discount_value']);

        $totalDiscountGiven = CouponUsageLog::sum('discount_amount');

        return [
            'total_coupons' => $totalCoupons,
            'active_coupons' => $activeCoupons,
            'expired_coupons' => $expiredCoupons,
            'most_used_coupons' => $mostUsedCoupons,
            'total_discount_given' => $totalDiscountGiven,
        ];
    }

    public function getUserCouponHistory(User $user): Collection
    {
        return CouponUsageLog::where('user_id', $user->id)
            ->with(['coupon', 'order'])
            ->orderBy('used_at', 'desc')
            ->get();
    }

    public function getCouponPerformance(Coupon $coupon): array
    {
        $usageLogs = $coupon->usageLogs()->with('order')->get();
        
        $totalRevenue = $usageLogs->sum(function ($log) {
            return $log->order->total ?? 0;
        });

        $totalDiscount = $usageLogs->sum('discount_amount');

        return [
            'total_uses' => $coupon->used_count,
            'total_revenue' => $totalRevenue,
            'total_discount' => $totalDiscount,
            'net_revenue' => $totalRevenue - $totalDiscount,
            'average_order_value' => $usageLogs->count() > 0 ? $totalRevenue / $usageLogs->count() : 0,
        ];
    }

    /**
     * Get all visible coupons with eligibility info for the current user and cart.
     * Returns an array of ['coupon' => Coupon, 'eligible' => bool, 'message' => string]
     */
    public function getAllCouponsWithEligibility($user, $cart): array
    {
        $cartTotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        $cartItems = collect($cart);
        $coupons = Coupon::where('is_active', true)
            ->where('show_to_user', true)
            ->get();
        $result = [];
        foreach ($coupons as $coupon) {
            $validation = $this->validateCoupon($coupon->code, $user, $cartTotal, $cartItems);
            $result[] = [
                'coupon' => $coupon,
                'eligible' => $validation['valid'],
                'message' => $validation['message'],
            ];
        }
        return $result;
    }
}
