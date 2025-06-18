<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Cart;
use App\Services\RazorpayService;

Route::get('/', function () {
    $categories = Category::with('subcategories')->where('is_active', true)->get();
    $featuredProducts = Product::with(['category', 'subcategory'])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->take(8)
        ->get();

    // Calculate cart count
    if (Auth::check()) {
        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
    } else {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return view('home', compact('categories', 'featuredProducts', 'cartCount'));
})->name('home');

Route::get('/products', function () {
    $query = Product::with(['category', 'subcategory'])->where('is_active', true);

    // Filter by category
    if (request()->filled('category')) {
        $query->where('category_id', request('category'));
    }

    // Filter by subcategory
    if (request()->filled('subcategory')) {
        $query->where('subcategory_id', request('subcategory'));
    }

    // Sort products
    if (request()->filled('sort')) {
        switch (request('sort')) {
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

    $products = $query->paginate(12);
    $categories = Category::with('subcategories')->where('is_active', true)->get();
    $subcategories = Subcategory::where('is_active', true)->get();

    // Calculate cart count
    if (Auth::check()) {
        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
    } else {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return view('products.index', compact('products', 'categories', 'subcategories', 'cartCount'));
})->name('products.index');

Route::get('/categories', function () {
    $categories = Category::with('subcategories')->where('is_active', true)->get();
    return view('categories.index', compact('categories'));
})->name('categories.index');

Route::get('/contact', function () {
    return view('contact');
});

// Combined Authentication Routes
Route::get('/auth', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('auth.combined');
})->name('auth.combined');

// Redirect old auth routes to new combined page
Route::get('/login', function () {
    return redirect()->route('auth.combined');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('auth.combined');
})->name('register');

// Keep the POST routes for actual authentication
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Dashboard route (protected)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Add to Cart route
Route::post('/cart/add', function (Request $request) {
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product_id = $request->input('product_id');
    $quantity = $request->input('quantity');

    if (Auth::check()) {
        // User is logged in, save to database
        $cartItem = \App\Models\Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            \App\Models\Cart::create([
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
            $product = \App\Models\Product::find($product_id);
            if ($product) {
                $cart[$product_id] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    // Add other relevant product details if needed
                ];
            }
        }
        $request->session()->put('cart', $cart);
    }

    return back()->with('success', 'Product added to cart!');

})->name('cart.add');

// Product details route
Route::get('/products/{product:slug}', function (\App\Models\Product $product) {
    // Ensure category and subcategory are loaded
    $product->load(['category', 'subcategory']);

    // Fetch related products (e.g., from the same category, excluding the current product)
    $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->with(['category', 'subcategory'])
        ->limit(4) // Limit the number of related products shown
        ->get();

    // Calculate cart count
    if (Auth::check()) {
        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
    } else {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return view('products.show', compact('product', 'relatedProducts', 'cartCount'));
})->name('products.show');

// Cart page route
Route::get('/cart', function () {
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
                // Add other relevant product details from $item->product if needed
            ]];
        })->toArray();
        $cartCount = $cartItems->sum('quantity');

    } else {
        // User is a guest, fetch from session
        $cart = session()->get('cart', []);
         // For guest cart, fetch full product details for items in session if not already stored
        $productIds = array_keys($cart);
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Merge session cart data with product details
        $cart = collect($cart)->map(function ($item, $productId) use ($products) {
            $product = $products->get($productId);
            if ($product) {
                $item['name'] = $product->name;
                $item['price'] = $product->price;
                // Add other details from $product to $item if needed
            }
            return $item;
        })->toArray();

        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return view('cart.index', compact('cart', 'cartCount'));
})->name('cart.index');

// Update cart item quantity route
Route::patch('/cart/update/{productId}', function (Request $request, $productId) {
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $quantity = $request->input('quantity');

    if (Auth::check()) {
        // Update quantity in database for logged-in users
        $cartItem = \App\Models\Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }
    } else {
        // Update quantity in session for guests
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
        }
    }

    return back()->with('success', 'Cart updated successfully!');
})->name('cart.update');

// Remove cart item route
Route::delete('/cart/remove/{productId}', function (Request $request, $productId) {
    if (Auth::check()) {
        // Remove item from database for logged-in users
        \App\Models\Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
    } else {
        // Remove item from session for guests
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
        }
    }

    return back()->with('success', 'Product removed from cart!');
})->name('cart.remove');

// Checkout routes
Route::get('/checkout', function () {
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to checkout.');
    }

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
            ]];
        })->toArray();
    } else {
        // User is a guest, fetch from session
        $cart = session()->get('cart', []);
        $productIds = array_keys($cart);
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cart = collect($cart)->map(function ($item, $productId) use ($products) {
            $product = $products->get($productId);
            if ($product) {
                $item['name'] = $product->name;
                $item['price'] = $product->price;
            }
            return $item;
        })->toArray();
    }

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    return view('checkout.index', compact('cart'));
})->name('checkout.index');

Route::post('/checkout/process', function (Request $request) {
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to checkout.');
    }

    $request->validate([
        'email' => 'required|email',
        'phone' => 'required',
        'shipping_address' => 'required',
        'billing_address' => 'required',
        'payment_method' => 'required|in:razorpay,cod',
    ]);

    // Get cart items
    $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with('product')->get();
    
    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    // Calculate total
    $total = $cartItems->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    // Ensure total is a valid number
    if (!is_numeric($total) || $total <= 0) {
        return redirect()->route('cart.index')->with('error', 'Invalid order amount.');
    }

    // Create order
    $order = \App\Models\Order::create([
        'user_id' => Auth::id(),
        'order_number' => 'ORD-' . strtoupper(uniqid()),
        'total_amount' => $total,
        'shipping_address' => $request->shipping_address,
        'billing_address' => $request->billing_address,
        'phone' => $request->phone,
        'email' => $request->email,
        'notes' => $request->notes,
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => $request->payment_method
    ]);

    // Create order items
    foreach ($cartItems as $item) {
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);
    }

    // Clear the cart
    \App\Models\Cart::where('user_id', Auth::id())->delete();

    if ($request->payment_method === 'cod') {
        // For Cash on Delivery, return success response
        return response()->json([
            'success' => true,
            'internal_order_id' => $order->id,
            'message' => 'Order placed successfully. Pay on delivery.'
        ]);
    }

    try {
        // Use Razorpay Service to create an order with Razorpay
        $razorpayService = new \App\Services\RazorpayService();
        $razorpayOrder = $razorpayService->createOrder($order);

        // Update order with Razorpay order ID
        $order->update([
            'razorpay_order_id' => $razorpayOrder->id
        ]);

        // Return a JSON response with the Razorpay order ID and total for the frontend
        return response()->json([
            'success' => true,
            'internal_order_id' => $order->id,
            'razorpay_order_id' => $razorpayOrder->id,
            'amount' => $razorpayOrder->amount, // Amount in paise
            'currency' => 'INR'
        ]);
    } catch (\Exception $e) {
        // If Razorpay order creation fails, update order status
        $order->update([
            'status' => 'failed',
            'payment_status' => 'failed'
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to create payment order. Please try again.'
        ], 500);
    }
})->name('checkout.process');

Route::get('/checkout/success/{order}', function (\App\Models\Order $order, Request $request) {
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    // Check if payment is already marked as paid
    if ($order->payment_status === 'paid') {
        return view('checkout.success', compact('order'));
    }

    // For Cash on Delivery orders
    if ($order->payment_method === 'cod') {
        // Update order status for COD
        $order->update([
            'status' => 'processing',
            'payment_status' => 'pending'
        ]);

        return view('checkout.success', compact('order'));
    }

    // For Razorpay orders, verify payment
    if (!$request->has(['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'])) {
        // If it's a COD order, don't redirect to failed page
        if ($order->payment_method === 'cod') {
            return view('checkout.success', compact('order'));
        }
        
        return redirect()->route('checkout.failed', ['order' => $order->id])
            ->with('error', 'Invalid payment parameters.');
    }

    // Verify payment
    $razorpayService = new \App\Services\RazorpayService();
    $isPaymentValid = $razorpayService->verifyPayment(
        $request->razorpay_payment_id,
        $request->razorpay_order_id,
        $request->razorpay_signature
    );

    if ($isPaymentValid) {
        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'razorpay_payment_id' => $request->razorpay_payment_id
        ]);

        return view('checkout.success', compact('order'));
    }

    // If payment verification fails, redirect to failed page
    return redirect()->route('checkout.failed', ['order' => $order->id])
        ->with('error', 'Payment verification failed. Please try again.');
})->name('checkout.success');

Route::get('/checkout/failed/{order}', function (\App\Models\Order $order) {
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    // Update order status to failed if not already failed
    if ($order->payment_status !== 'failed') {
        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled'
        ]);
    }

    return view('checkout.failed', compact('order'));
})->name('checkout.failed');

// User Orders Route
Route::get('/my-orders', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $orders = \App\Models\Order::with(['items.product'])
        ->where('user_id', Auth::id())
        ->latest()
        ->paginate(10);

    return view('orders.index', compact('orders'));
})->name('orders.index');
