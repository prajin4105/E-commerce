<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'prajinshingala4105@gmial.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'email' => 'prajinshingala4105@gmial.com',
                'name' => 'Prajin Shingala',
                'password' => bcrypt('password'),
            ]);
        }

        $products = Product::all();
        $statuses = ['placed', 'on_the_way', 'delivered', 'cancelled', 'returned'];
        $paymentStatuses = ['paid', 'pending', 'cod'];
        $addresses = [
            '123 Main St, Surat, Gujarat',
            '456 Market Rd, Ahmedabad, Gujarat',
            '789 City Center, Rajkot, Gujarat',
        ];
        $phone = '9876543210';

        for ($i = 0; $i < 10; $i++) {
            $orderProducts = $products->random(rand(1, 3));
            $total = 0;
            $order = Order::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'phone_number' => $phone,
                'status' => $statuses[array_rand($statuses)],
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'payment_method' => 'cod',
                'shipping_address' => $addresses[array_rand($addresses)],
                'billing_address' => $addresses[array_rand($addresses)],
            ]);
            foreach ($orderProducts as $product) {
                $qty = rand(1, 3);
                $price = $product->price;
                $total += $qty * $price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);
            }
            $order->update([
                'total_price' => $total,
                'total_amount' => $total,
            ]);
        }
    }
} 