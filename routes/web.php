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
    return view('home', compact('categories', 'featuredProducts'));
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

    return view('products.index', compact('products', 'categories', 'subcategories'));
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
