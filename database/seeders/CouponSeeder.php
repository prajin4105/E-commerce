<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fixed amount discount coupon
        Coupon::create([
            'code' => 'SAVE100',
            'description' => 'Get ₹100 off on your order',
            'discount_type' => 'fixed',
            'discount_value' => 100.00,
            'minimum_cart_value' => 500.00,
            'max_uses' => 50,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addMonths(3),
            'is_active' => true,
            'free_shipping' => false,
            'exclusive' => false,
        ]);

        // Percentage discount coupon
        Coupon::create([
            'code' => 'SAVE20',
            'description' => 'Get 20% off on your order',
            'discount_type' => 'percent',
            'discount_value' => 20.00,
            'minimum_cart_value' => 1000.00,
            'max_uses' => 100,
            'used_count' => 0,
            'per_user_limit' => 2,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addMonths(6),
            'is_active' => true,
            'free_shipping' => false,
            'exclusive' => false,
        ]);

        // Free shipping coupon
        Coupon::create([
            'code' => 'FREESHIP',
            'description' => 'Free shipping on orders above ₹2000',
            'discount_type' => 'fixed',
            'discount_value' => 0.00,
            'minimum_cart_value' => 2000.00,
            'max_uses' => 200,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addYear(),
            'is_active' => true,
            'free_shipping' => true,
            'exclusive' => false,
        ]);

        // Small discount for testing
        Coupon::create([
            'code' => 'WELCOME50',
            'description' => 'Welcome discount - ₹50 off',
            'discount_type' => 'fixed',
            'discount_value' => 50.00,
            'minimum_cart_value' => 200.00,
            'max_uses' => 500,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addMonths(12),
            'is_active' => true,
            'free_shipping' => false,
            'exclusive' => false,
        ]);

        // High value discount
        Coupon::create([
            'code' => 'BIGSAVE',
            'description' => 'Big savings - ₹500 off on orders above ₹3000',
            'discount_type' => 'fixed',
            'discount_value' => 500.00,
            'minimum_cart_value' => 3000.00,
            'max_uses' => 25,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addMonths(2),
            'is_active' => true,
            'free_shipping' => false,
            'exclusive' => true,
        ]);

        // Expired coupon for testing
        Coupon::create([
            'code' => 'EXPIRED',
            'description' => 'This coupon has expired',
            'discount_type' => 'fixed',
            'discount_value' => 100.00,
            'minimum_cart_value' => 500.00,
            'max_uses' => 10,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::now()->subMonths(2),
            'valid_to' => Carbon::now()->subMonth(),
            'is_active' => true,
            'free_shipping' => false,
            'exclusive' => false,
        ]);

        // Inactive coupon for testing
        Coupon::create([
            'code' => 'INACTIVE',
            'description' => 'This coupon is inactive',
            'discount_type' => 'percent',
            'discount_value' => 15.00,
            'minimum_cart_value' => 1000.00,
            'max_uses' => 50,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addMonths(3),
            'is_active' => false,
            'free_shipping' => false,
            'exclusive' => false,
        ]);

        // Remove any existing SECRET50 coupon to avoid duplicates
        Coupon::where('code', 'SECRET50')->delete();
        // Manual-only hidden coupon
        Coupon::create([
            'code' => 'SECRET50',
            'description' => 'Special ₹50 off for secret users',
            'discount_type' => 'fixed',
            'discount_value' => 50.00,
            'minimum_cart_value' => 100.00,
            'max_uses' => 100,
            'used_count' => 0,
            'per_user_limit' => 2,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now()->addMonths(3),
            'is_active' => true,
            'show_to_user' => false,
            'free_shipping' => false,
            'exclusive' => false,
        ]);
    }
}
