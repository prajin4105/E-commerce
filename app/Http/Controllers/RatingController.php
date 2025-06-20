<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Check if user has purchased the product
        $userHasPurchased = Auth::user()->orders()->whereHas('items', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->where('status', 'delivered')->exists();

        if (!$userHasPurchased) {
            return back()->with('error', 'You can only review products you have purchased.');
        }

        // Check if user has already reviewed the product
        $existingRating = $product->ratings()->where('user_id', Auth::id())->first();

        if ($existingRating) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $product->ratings()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
