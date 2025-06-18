@extends('layouts.app')

@section('title', 'Home - Your E-Commerce Store')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gray-900 h-[600px]">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-50" src="https://plus.unsplash.com/premium_photo-1684785617500-fb22234eeedd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Hero background">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Welcome to E-com</h1>
            <p class="mt-6 text-xl text-gray-300 max-w-3xl">Discover amazing products at unbeatable prices. Shop the latest trends and find your perfect style.</p>
            <div class="mt-10">
                <a href="/products" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-md text-lg font-medium hover:bg-blue-700">Shop Now</a>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Shop by Category</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="w-full h-48 bg-gray-200">
                        @if($category->image)
                            <img src="{{ asset('storageil,' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($category->name) }}'">
                        @else
                            <img src="https://via.placeholder.com/400x300?text={{ urlencode($category->name) }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover">
                        @endif
                    </div>

                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2">{{ $category->name }}</h2>
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>

                        <!-- @if($category->subcategories->count() > 0)
                            <div class="mt-4">
                                <h3 class="text-lg font-medium mb-2">Subcategories:</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($category->subcategories as $subcategory)
                                        <a href="#" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                                            {{ $subcategory->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif -->


                    <a href="{{ route('products.index', ['category' => $category->id]) }}"
                       class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        View All Products
                    </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Products -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="group">
                        <div class="relative rounded-lg overflow-hidden">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($product->name) }}'">
                            @else
                                <img src="https://via.placeholder.com/400x300?text={{ urlencode($product->name) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                            @endif
                            <div class="absolute top-2 right-2">
x                                {{-- <button class="bg-white p-2 rounded-full shadow-md hover:bg-gray-100"> --}}
                                    <i class="far fa-heart text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @php
                                    $categoryName = $product->category ? $product->category->name : 'Uncategorized';
                                    $subcategoryName = $product->subcategory ? $product->subcategory->name : '';
                                @endphp
                                {{-- Display category and subcategory with a separator only if both exist and category is not 'Uncategorized' --}}
                                {{ $categoryName }}
                                @if($product->category && $product->subcategory)
                                    -
                                @endif
                                {{ $subcategoryName }}
                            </p>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                                {{-- <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">  </button> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-blue-600">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to start shopping?</span>
                <span class="block text-blue-200">Sign up for exclusive offers today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="/register" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                        Get started
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
