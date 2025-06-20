<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportProducts extends Command
{
    protected $signature = 'products:import';
    protected $description = 'Import products with images from storage/app/public/products';

    public function handle()
    {
        $this->info('Starting product import...');

        // Get all image files from the products directory
        $imageFiles = Storage::disk('public')->files('products');
        
        if (empty($imageFiles)) {
            $this->error('No images found in products directory!');
            return;
        }

        // Get or create a default category
        $category = Category::firstOrCreate(
            ['name' => 'Default Category'],
            [
                'slug' => 'default-category',
                'description' => 'Default category for imported products',
                'is_active' => true
            ]
        );

        // Get or create a default subcategory
        $subcategory = Subcategory::firstOrCreate(
            [
                'name' => 'Default Subcategory',
                'category_id' => $category->id
            ],
            [
                'slug' => 'default-subcategory',
                'description' => 'Default subcategory for imported products',
                'is_active' => true
            ]
        );

        $bar = $this->output->createProgressBar(count($imageFiles));
        $bar->start();

        foreach ($imageFiles as $imageFile) {
            // Generate a product name from the image filename
            $filename = basename($imageFile);
            $name = Str::title(str_replace(['.', '_', '-'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
            
            // Generate a unique SKU
            $sku = 'SKU-' . strtoupper(Str::random(8));
            
            // Create the product
            $product = Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sku' => $sku,
                'description' => "Product description for {$name}",
                'price' => rand(1000, 10000) / 100, // Random price between 10 and 100
                'stock' => rand(0, 100),
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'is_active' => true,
                'image' => $imageFile, // Store the relative path
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Products imported successfully!');
    }
} 