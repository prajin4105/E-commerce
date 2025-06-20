<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Remove previous category creation logic and create exactly 2 categories, each with 5 subcategories
        $categories = [
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'image' => 'home-kitchen.jpg',
                'description' => 'Everything for your home and kitchen.',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Cookware', 'image' => 'cookware.jpg', 'description' => 'Pots, pans, and more.'],
                    ['name' => 'Home Decor', 'image' => 'decor.jpg', 'description' => 'Decorative items for your home.'],
                    ['name' => 'Furniture', 'image' => 'furniture.jpg', 'description' => 'Chairs, tables, and more.'],
                    ['name' => 'Dining', 'image' => 'dining.jpg', 'description' => 'Dining sets and accessories.'],
                    ['name' => 'Storage', 'image' => 'storage.jpg', 'description' => 'Storage solutions.'],
                ],
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'image' => 'sports-outdoors.jpg',
                'description' => 'Gear for sports and outdoor activities.',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Fitness Equipment', 'image' => 'fitness.jpg', 'description' => 'Equipment for fitness.'],
                    ['name' => 'Outdoor Gear', 'image' => 'outdoor.jpg', 'description' => 'Gear for outdoor adventures.'],
                    ['name' => 'Sportswear', 'image' => 'sportswear.jpg', 'description' => 'Clothing for sports.'],
                    ['name' => 'Cycling', 'image' => 'cycling.jpg', 'description' => 'Bikes and accessories.'],
                    ['name' => 'Camping', 'image' => 'camping.jpg', 'description' => 'Camping essentials.'],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $cat = Category::create([
                'name' => $catData['name'],
                'slug' => $catData['slug'],
                'image' => $catData['image'],
                'description' => $catData['description'],
                'is_active' => $catData['is_active'],
            ]);
            foreach ($catData['subcategories'] as $subcatData) {
                Subcategory::create([
                    'name' => $subcatData['name'],
                    'slug' => $catData['slug'] . '-' . Str::slug($subcatData['name']),
                    'image' => $subcatData['image'],
                    'description' => $subcatData['description'],
                    'category_id' => $cat->id,
                    'is_active' => true,
                ]);
            }
        }
    }
} 