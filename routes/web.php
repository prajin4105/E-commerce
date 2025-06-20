<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\RatingController;
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
use App\Http\Controllers\ContactController;

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
})->name('products.index')->middleware('web');

Route::get('/categories', function () {
    $categories = Category::with('subcategories')->where('is_active', true)->get();
    return view('categories.index', compact('categories'));
})->name('categories.index');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::post('login', [App\Http\Controllers\Auth\OtpController::class, 'sendOtp'])->name('login.submit');
    
    // OTP Routes
    Route::get('/verify-otp', [App\Http\Controllers\Auth\OtpController::class, 'showVerifyForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('/resend-otp', [App\Http\Controllers\Auth\OtpController::class, 'resendOtp'])->name('resend.otp');

    // Register Route
    Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register');

    // Social Login Routes
    Route::get('auth/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('auth/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);
    Route::get('auth/microsoft', [App\Http\Controllers\Auth\LoginController::class, 'redirectToMicrosoft'])->name('login.microsoft');
    Route::get('auth/microsoft/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleMicrosoftCallback']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Orders route
    Route::get('/orders', function () {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->get();
        return view('orders.index', compact('orders'));
    })->name('orders.index');

    Route::post('/orders/{order}/send-bill', [OrderController::class, 'sendBill'])->name('orders.send-bill');
    Route::post('/orders/{order}/request-return', [OrderController::class, 'requestReturn'])->name('orders.request-return');

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::post('/products/{product}/ratings', [RatingController::class, 'store'])->name('products.ratings.store');

    // Cancel Order Route
    Route::post('/orders/{order}/cancel', function ($orderId) {
        $order = \App\Models\Order::findOrFail($orderId);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        if (!method_exists($order, 'canBeCancelled') || !$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Order cannot be cancelled.');
        }
        $order->status = 'cancelled';
        $order->save();
        // Send cancellation email
        \Mail::to($order->email)->send(new \App\Mail\OrderStatusChanged($order, 'Your order has been cancelled.'));
        return redirect()->back()->with('success', 'Order cancelled successfully.');
    })->name('orders.cancel')->middleware(['auth']);
});

require __DIR__.'/auth.php';

// Cart routes
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
                'image' => $item->product->images[0] ?? null,
            ]];
        })->toArray();
        $cartCount = $cartItems->sum('quantity');
    } else {
        // User is a guest, fetch from session
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return view('cart.index', compact('cart', 'cartCount'));
})->name('cart.index');

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
                    'image' => $product->images[0] ?? null,
                ];
            }
        }
        $request->session()->put('cart', $cart);
    }

    return back()->with('success', 'Product added to cart!');
})->name('cart.add');

Route::post('/cart/update', function (Request $request) {
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:0',
    ]);

    $product_id = $request->input('product_id');
    $quantity = $request->input('quantity');

    $product = \App\Models\Product::find($product_id);
    if ($product && $quantity > $product->stock) {
        return back()->withErrors(['quantity' => 'Cannot order more than available stock ('.$product->stock.').']);
    }

    if (Auth::check()) {
        if ($quantity > 0) {
            \App\Models\Cart::where('user_id', Auth::id())
                ->where('product_id', $product_id)
                ->update(['quantity' => $quantity]);
        } else {
            \App\Models\Cart::where('user_id', Auth::id())
                ->where('product_id', $product_id)
                ->delete();
        }
    } else {
        $cart = $request->session()->get('cart', []);
        if ($quantity > 0) {
            $cart[$product_id]['quantity'] = $quantity;
        } else {
            unset($cart[$product_id]);
        }
        $request->session()->put('cart', $cart);
    }

    return back()->with('success', 'Cart updated successfully!');
})->name('cart.update');

Route::post('/cart/remove', function (Request $request) {
    $request->validate([
        'product_id' => 'required|exists:products,id',
    ]);

    $product_id = $request->input('product_id');

    if (Auth::check()) {
        \App\Models\Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();
    } else {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product_id]);
        $request->session()->put('cart', $cart);
    }

    return back()->with('success', 'Product removed from cart!');
})->name('cart.remove');

// Product details route
Route::get('/products/{id}', function ($id) {
    $product = Product::findOrFail($id);
    
    if (!$product->is_active) {
        abort(404);
    }

    // Ensure category and subcategory are loaded
    $product->load(['category', 'subcategory']);

    // Fetch related products (e.g., from the same category, excluding the current product)
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->with(['category', 'subcategory'])
        ->limit(4)
        ->get();

    // Calculate cart count and check wishlist status
    $isInWishlist = false;
    if (Auth::check()) {
        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
        $isInWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
    } else {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return view('products.show', compact('product', 'relatedProducts', 'cartCount', 'isInWishlist'));
})->name('products.show');

// Checkout routes
Route::get('/checkout', function () {
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

    return view('checkout.index', compact('cart'));
})->middleware(['auth'])->name('checkout.index');

Route::post('/checkout/process', function (Request $request) {
    try {
        \Log::info('Checkout request data:', ['all_data' => $request->all(), 'phone' => $request->phone, 'email' => $request->email]);
        
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'shipping_address' => 'required',
            'billing_address' => 'required',
            'payment_method' => 'required|in:cod,razorpay'
        ]);

        // Fetch cart depending on user status
        if (auth()->check()) {
            // Logged-in user: fetch from database
            $cartItems = \App\Models\Cart::where('user_id', auth()->id())->with('product')->get();
            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty']);
            }
            $cart = [];
            $total = 0;
            foreach ($cartItems as $item) {
                $cart[$item->product_id] = [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->images[0] ?? null,
                ];
                $total += $item->product->price * $item->quantity;
            }
        } else {
            // Guest: fetch from session
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return response()->json(['success' => false, 'message' => 'Cart is empty']);
            }
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        // Create order first
        $orderData = [
            'user_id' => auth()->id(),
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'total_amount' => $total,
            'shipping_address' => $validated['shipping_address'],
            'billing_address' => $validated['billing_address']
        ];
        
        \Log::info('Creating order with data:', $orderData);
        
        $order = \App\Models\Order::create($orderData);
        
        // Send order placed email
        \Mail::to($order->email)->send(new \App\Mail\OrderPlaced($order));
        
        \Log::info('Order created:', ['order_id' => $order->id, 'phone_number' => $order->phone_number]);

        // Add order details
        foreach ($cart as $id => $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
            // Reduce product stock
            $product = \App\Models\Product::find($id);
            if ($product) {
                $product->stock = max(0, $product->stock - $item['quantity']);
                $product->save();
            }
        }

        if ($request->payment_method === 'cod') {
            // For COD, update order status and clear cart
            $order->update([
                'status' => 'placed',
                'payment_status' => 'cod'
            ]);
            if (auth()->check()) {
                \App\Models\Cart::where('user_id', auth()->id())->delete();
            } else {
                session()->forget('cart');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order_id' => $order->id
            ]);
        } else {
            // For Razorpay, create payment order
            $razorpayService = new \App\Services\RazorpayService();
            $razorpayOrder = $razorpayService->createOrder($order->id, $total);
            
            if (!$razorpayOrder) {
                $order->delete(); // Clean up the order if Razorpay order creation fails
                return response()->json(['success' => false, 'message' => 'Failed to create payment order']);
            }
            // Save the Razorpay order ID to the order
            $order->razorpay_order_id = $razorpayOrder['id'];
            $order->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment order created successfully',
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency']
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Checkout process error:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
});

// New route to handle payment success and create order
Route::post('/checkout/payment-success', function (Request $request) {
    try {
        $validated = $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'shipping_address' => 'required',
            'billing_address' => 'required'
        ]);

        $razorpayService = new \App\Services\RazorpayService();
        
        // Verify the payment
        if (!$razorpayService->verifyPayment(
            $validated['razorpay_payment_id'],
            $validated['razorpay_order_id'],
            $validated['razorpay_signature']
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);
        }

        // Get the order from Razorpay order ID
        $order = \App\Models\Order::where('razorpay_order_id', $validated['razorpay_order_id'])->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Update order status
        $order->update([
            'status' => 'placed',
            'payment_status' => 'paid',
            'shipping_address' => $validated['shipping_address'],
            'billing_address' => $validated['billing_address']
        ]);

        // Clear the cart
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Payment successful',
            'internal_order_id' => $order->id
        ]);
    } catch (\Exception $e) {
        \Log::error('Payment success error:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/checkout/success/{orderId}', function ($orderId) {
    $order = \App\Models\Order::findOrFail($orderId);
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    // Do not overwrite status or payment_status here, just show the order
    return view('checkout.success', compact('order'));
})->middleware(['auth'])->name('checkout.success');

Route::get('/checkout/failed/{orderId}', function ($orderId) {
    $order = \App\Models\Order::findOrFail($orderId);
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

// Social Login Routes
Route::get('auth/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

Route::get('auth/microsoft', [App\Http\Controllers\Auth\LoginController::class, 'redirectToMicrosoft'])->name('login.microsoft');
Route::get('auth/microsoft/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleMicrosoftCallback']);

// OTP Routes
Route::post('/send-otp', [App\Http\Controllers\Auth\OtpController::class, 'sendOtp'])->name('send.otp');
Route::get('/verify-otp', [App\Http\Controllers\Auth\OtpController::class, 'showVerifyForm'])->name('verify.otp.form');
Route::post('/verify-otp', [App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/resend-otp', [App\Http\Controllers\Auth\OtpController::class, 'resendOtp'])->name('resend.otp');

// Buy Now route
Route::post('/cart/buy-now', [\App\Http\Controllers\CartController::class, 'buyNow'])->name('cart.buyNow');
