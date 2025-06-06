@extends('layouts.app')

@section('title', 'All Products - Your E-Commerce Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">All Products</h1>

    <!-- Filters -->
    <div class="mb-8">
        <form id="filterForm" action="{{ route('products.index') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="category" id="categorySelect" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    @if($category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endif
                @endforeach
            </select>

            <select name="subcategory" id="subcategorySelect" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">All Subcategories</option>
                @if(request('category'))
                    {{-- Show subcategories for the selected category --}}
                    @php
                        $selectedCategory = $categories->firstWhere('id', request('category'));
                    @endphp
                    @if($selectedCategory)
                        @foreach($selectedCategory->subcategories as $subcategory)
                            @if($subcategory)
                                <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                @else
                    {{-- Show all subcategories when no category is selected --}}
                    @foreach($subcategories as $subcategory)
                        @if($subcategory)
                            <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>

            <select name="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">Sort By</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
            </select>

            @if(request()->hasAny(['category', 'subcategory', 'sort']))
                <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-12">
            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
            <p class="text-gray-500">Try adjusting your filters or check back later for new products.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="group">
                    <div class="relative rounded-lg overflow-hidden">
                        @php
                            $imageUrl = $product->images && is_array($product->images) && !empty($product->images) 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://via.placeholder.com/400x300?text=' . urlencode($product->name);
                        @endphp
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($product->name) }}'">
                        </a>
                        <div class="absolute top-2 right-2">
                            <button class="bg-white p-2 rounded-full shadow-md hover:bg-gray-100">
                                <i class="far fa-heart text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:underline">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @php
                                $categoryName = $product->category ? $product->category->name : 'Uncategorized';
                                $subcategoryName = $product->subcategory ? $product->subcategory->name : '';
                            @endphp
                            {{ $categoryName }}
                            @if($product->category && $product->subcategory)
                                -
                            @endif
                            {{ $subcategoryName }}
                        </p>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1"> {{-- Default quantity to 1 --}}
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection 