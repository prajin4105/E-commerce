<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'image' => '01JWXD0JX7AN3X7QK5CZDM7V8E.jpg',
                'description' => 'Latest smartphones and accessories.',
                'category_id' => 1, // Electronics
                'is_active' => true,
            ],
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'image' => '01JWXD0JWYC3QNK0SSHQJYX5E9.jpg',
                'description' => 'Laptops and notebooks.',
                'category_id' => 1, // Electronics
                'is_active' => true,
            ],
            [
                'name' => 'T-Shirts',
                'slug' => 't-shirts',
                'image' => '01JWXCYFP4VNPVKXZVV5F402KB.jpg',
                'description' => 'Casual and formal t-shirts.',
                'category_id' => 2, // Clothing
                'is_active' => true,
            ],
            [
                'name' => 'Jeans',
                'slug' => 'jeans',
                'image' => '01JWXC3ZSNTSAQB1Z9RBVXVDAX.jpg',
                'description' => 'Denim jeans and trousers.',
                'category_id' => 2, // Clothing
                'is_active' => true,
            ],
            [
                'name' => 'Fiction',
                'slug' => 'fiction',
                'image' => '01JWXC1PBWJ0GCHC69TCW2511T.jpg',
                'description' => 'Fiction books and novels.',
                'category_id' => 3, // Books
                'is_active' => true,
            ],
            [
                'name' => 'Non-Fiction',
                'slug' => 'non-fiction',
                'image' => '01JWXC06TADCK144S1J23WDP52.jpg',
                'description' => 'Non-fiction books and educational material.',
                'category_id' => 3, // Books
                'is_active' => true,
            ],
        ];

        foreach ($subCategories as $subCategory) {
            Subcategory::create($subCategory);
        }
    }
} 