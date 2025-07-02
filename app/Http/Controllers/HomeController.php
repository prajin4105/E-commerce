<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->get();
        $featuredProducts = Product::where('is_featured', true)
            ->with(['category', 'subcategory'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
} 