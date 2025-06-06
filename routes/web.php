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

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        return redirect()->route('home');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});

// Logout route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/dashboard');
})->name('logout');

// Registration routes
Route::get('/signup', function () {
    return view('auth.register');
})->name('signup');

Route::post('/signup', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Redirect to login after registration
    return redirect()->route('login')->with('success', 'Registration successful. Please login.');
})->name('signup.post');

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
