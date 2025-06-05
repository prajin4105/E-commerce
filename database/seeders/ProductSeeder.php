<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sampleImage = 'products/sample.jpg'; // You can place a sample image in storage/app/public/products/sample.jpg

        foreach (Subcategory::all() as $subcategory) {
            for ($i = 1; $i <= 2; $i++) {
                Product::create([
                    'name' => $subcategory->name . ' Product ' . $i,
                    'slug' => Str::slug($subcategory->name . '-product-' . $i) . '-' . Str::random(6),
                    'description' => 'This is a sample product for ' . $subcategory->name . '.',
                    'price' => rand(1000, 5000) / 100,
                    'stock' => rand(10, 100),
                    'images' => json_encode([$sampleImage]),
                    'category_id' => $subcategory->category_id,
                    'subcategory_id' => $subcategory->id,
                    'is_featured' => false,
                    'is_active' => true,
                ]);
            }
        }
    }
} 