<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');

        if (Auth::check()) {
            // User is logged in, save to database
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            // User is a guest, save to session
            $cart = $request->session()->get('cart', []);

            if (isset($cart[$product_id])) {
                $cart[$product_id]['quantity'] += $quantity;
            } else {
                $product = Product::find($product_id);
                if ($product) {
                    $cart[$product_id] = [
                        'product_id' => $product_id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $quantity,
                        'image' => $product->images[0] ?? null,
                    ];
                }
            }
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('checkout.index');
    }
} 