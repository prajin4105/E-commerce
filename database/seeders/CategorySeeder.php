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
        // Create main categories
        $men = Category::create([
            'name' => 'Men',
            'slug' => 'men',
            'description' => 'Men\'s fashion and accessories',
            'is_active' => true,
        ]);

        $women = Category::create([
            'name' => 'Women',
            'slug' => 'women',
            'description' => 'Women\'s fashion and accessories',
            'is_active' => true,
        ]);

        $kids = Category::create([
            'name' => 'Kids',
            'slug' => 'kids',
            'description' => 'Kids\' fashion and accessories',
            'is_active' => true,
        ]);

        // Create subcategories for Men
        $menSubcategories = [
            'T-Shirts',
            'Shirts',
            'Pants',
            'Jeans',
            'Jackets',
            'Shoes',
            'Accessories',
        ];

        foreach ($menSubcategories as $subcategory) {
            Subcategory::create([
                'name' => $subcategory,
                'slug' => 'men-' . Str::slug($subcategory),
                'description' => "Men's {$subcategory}",
                'category_id' => $men->id,
                'is_active' => true,
            ]);
        }

        // Create subcategories for Women
        $womenSubcategories = [
            'Dresses',
            'Tops',
            'Skirts',
            'Pants',
            'Jeans',
            'Shoes',
            'Accessories',
            'Bags',
        ];

        foreach ($womenSubcategories as $subcategory) {
            Subcategory::create([
                'name' => $subcategory,
                'slug' => 'women-' . Str::slug($subcategory),
                'description' => "Women's {$subcategory}",
                'category_id' => $women->id,
                'is_active' => true,
            ]);
        }

        // Create subcategories for Kids
        $kidsSubcategories = [
            'Boys Clothing',
            'Girls Clothing',
            'Infant Clothing',
            'Shoes',
            'Accessories',
            'Toys',
        ];

        foreach ($kidsSubcategories as $subcategory) {
            Subcategory::create([
                'name' => $subcategory,
                'slug' => 'kids-' . Str::slug($subcategory),
                'description' => "Kids' {$subcategory}",
                'category_id' => $kids->id,
                'is_active' => true,
            ]);
        }
    }
} 