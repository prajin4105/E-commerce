<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Product $product)
    {
        if (Auth::check()) {
            $wishlistItem = Wishlist::where('user_id', Auth::id())
                                    ->where('product_id', $product->id)
                                    ->first();

            if ($wishlistItem) {
                // Product is in the wishlist, so remove it
                $wishlistItem->delete();
                return back()->with('success', 'Product removed from your wishlist.');
            } else {
                // Product is not in the wishlist, so add it
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ]);
                return back()->with('success', 'Product added to your wishlist!');
            }
        }

        return redirect()->route('login')->with('error', 'You must be logged in to manage your wishlist.');
    }
}
