<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Add 3 products for each subcategory under the two main categories
        $mainCategorySlugs = ['home-kitchen', 'sports-outdoors'];
        foreach ($mainCategorySlugs as $catSlug) {
            $category = \App\Models\Category::where('slug', $catSlug)->first();
            if ($category) {
                $subcategories = \App\Models\Subcategory::where('category_id', $category->id)->get();
                foreach ($subcategories as $subcategory) {
                    for ($i = 1; $i <= 3; $i++) {
                        $name = $subcategory->name . ' Product ' . $i;
                        $sku = strtoupper(substr($catSlug, 0, 2)) . '-' . strtoupper(substr($subcategory->name, 0, 2)) . '-' . sprintf('%03d', $i);
                        $image = 'product-' . $catSlug . '-' . $subcategory->slug . '-' . $i . '.jpg';
                        $gallery = [
                            'gallery-' . $catSlug . '-' . $subcategory->slug . '-' . $i . '-1.jpg',
                            'gallery-' . $catSlug . '-' . $subcategory->slug . '-' . $i . '-2.jpg',
                        ];
                        Product::create([
                            'name' => $name,
                            'slug' => Str::slug($name),
                            'description' => 'Description for ' . $name,
                            'price' => rand(100, 1000),
                            'sale_price' => rand(80, 900),
                            'sku' => $sku,
                            'stock' => rand(10, 100),
                            'image' => $image,
                            'gallery' => $gallery,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'is_active' => true,
                            'is_featured' => (bool)rand(0, 1),
                        ]);
                    }
                }
            }
        }
    }
} 