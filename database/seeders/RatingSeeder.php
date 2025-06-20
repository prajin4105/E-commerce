<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are users to assign reviews
        if (User::count() < 5) {
            User::factory(5)->create();
        }
        $users = User::all();
        $products = Product::all();
        foreach ($products as $product) {
            // Each product gets 2-4 reviews
            foreach ($users->random(rand(2, 4)) as $user) {
                Rating::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => rand(3, 5),
                    'review' => fake()->sentence(10),
                ]);
            }
        }
    }
} 