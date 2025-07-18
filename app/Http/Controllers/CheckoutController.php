<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Services\CouponService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Fetch cart data based on user authentication
        if (Auth::check()) {
            // User is logged in, fetch from database
            $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with('product')->get();
            // Structure the data similarly to the session cart for easier view handling
            $cart = $cartItems->mapWithKeys(function($item) {
                return [$item->product_id => [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->images[0] ?? null,
                ]];
            })->toArray();
        } else {
            // User is a guest, fetch from session
            $cart = session()->get('cart', []);
        }
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        // Get all coupons with eligibility info
        $allCouponsWithEligibility = $this->couponService->getAllCouponsWithEligibility($user, $cart);
        return view('checkout.index', compact('cart', 'allCouponsWithEligibility'));
    }
    
    public function process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'shipping_address' => 'required',
            'billing_address' => 'required',
            'payment_method' => 'required|in:razorpay,cod',
            'coupon_id' => 'nullable|exists:coupons,id'
        ]);
        
        $user = Auth::user();
        $cart = session()->get('cart', []);
        $cartTotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        
        // Validate and apply coupon if provided
        $discount = 0;
        $coupon = null;
        
        if ($request->coupon_id) {
            $coupon = Coupon::find($request->coupon_id);
            if ($coupon) {
                $validation = $this->couponService->validateCoupon($coupon->code, $user, $cartTotal, collect($cart));
                if ($validation['valid']) {
                    $discount = $coupon->calculateDiscount($cartTotal);
                }
            }
        }
        
        $finalTotal = $cartTotal - $discount;
        
        // Create order logic here...
        // This is where you would create the order with the discount applied
        // You should also call $this->couponService->recordCouponUsage($coupon, $user, $order, $discount) after order creation

        // Clear the cart after successful order
        if (Auth::check()) {
            \App\Models\Cart::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
        
        return response()->json([
            'success' => true,
            'amount' => $finalTotal * 100, // Convert to paise for Razorpay
            'currency' => 'INR',
            'order_id' => 'order_' . time(), // Generate actual order ID
            'message' => 'Order processed successfully'
        ]);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $user = Auth::user();
        // Always use session cart for validation
        $cart = session()->get('cart', []);
        $cartTotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // Debug log for cart and coupon validation
        \Log::info('Coupon validation debug', [
            'user_id' => $user ? $user->id : null,
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'coupon_code' => $request->coupon_code,
        ]);

        $validation = $this->couponService->validateCoupon($request->coupon_code, $user, $cartTotal, collect($cart));

        if ($validation['valid']) {
            $coupon = $validation['coupon'];
            $discount = $coupon->calculateDiscount($cartTotal);
            
            return response()->json([
                'success' => true,
                'message' => $validation['message'],
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => $coupon->discount_value,
                    'discount_amount' => $discount,
                    'description' => $coupon->description,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $validation['message']
        ], 400);
    }
}
