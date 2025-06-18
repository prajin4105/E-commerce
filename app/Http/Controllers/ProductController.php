<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory'])->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by subcategory
        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        // Sort products
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        // $products = $query->inRandomOrderpaginate(12);
        //ramdom products
     $products = $query->inRandomOrder()->paginate(12);



        $categories = Category::where('is_active', true)->get();
        $subcategories = Subcategory::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'subcategories'));
    }
}
