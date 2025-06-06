@extends('layouts.app')

@section('title', $product->name . ' - Your E-Commerce Store')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Product Details Section --}}
    <div class="lg:flex lg:gap-6">
        <!-- Product Images -->
        <div class="lg:w-5/12">
            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <img src="{{ asset('storage/' . $product->images[0]) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-64 object-cover rounded-md shadow"
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/500x300?text={{ urlencode($product->name) }}'">
            @else
                <img src="https://via.placeholder.com/500x300?text={{ urlencode($product->name) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-64 object-cover rounded-md shadow">
            @endif
        </div>

        <!-- Product Details -->
        <div class="lg:w-7/12 mt-6 lg:mt-0">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

            {{-- Box for Price, Category, Subcategory, Stock --}}
            <div class="border border-gray-200 rounded-md p-4 mb-6">
                <p class="text-2xl font-bold text-blue-600 mb-4">${{ number_format($product->price, 2) }}</p>

                <div class="text-gray-600 text-sm mb-4">
                    @php
                        $categoryName = $product->category ? $product->category->name : 'Uncategorized';
                        $subcategoryName = $product->subcategory ? $product->subcategory->name : '';
                    @endphp
                    <span class="font-semibold">Category:</span> {{ $categoryName }}
                    @if($product->category && $product->subcategory)
                        -
                    @endif
                    @if($product->subcategory)
                        <span class="font-semibold">Subcategory:</span> {{ $subcategoryName }}
                    @endif
                </div>

                 {{-- Stock Information --}}
                @if($product->stock !== null)
                    <div class="text-sm text-gray-700">
                        <span class="font-semibold">Availability:</span> 
                        @if($product->stock > 0)
                            In Stock ({{ $product->stock }} items)
                        @else
                            Out of Stock
                        @endif
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Description</h2>
                <p class="text-gray-700 leading-relaxed text-sm">{{ $product->description }}</p>
            </div>

            {{-- Add to Cart Form --}}
            <form action="{{ route('cart.add') }}" method="POST" class="mb-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center mb-3">
                    <label for="quantity" class="mr-2 text-gray-700 text-sm font-medium">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-16 border border-gray-300 rounded-md text-sm px-2 py-1 shadow-sm focus:ring-blue-400 focus:border-blue-400">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 text-sm rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400 focus:ring-opacity-50">Add to Cart</button>
            </form>

             {{-- Add more product information here (e.g., SKU, brand, etc.) --}}
        </div>
    </div>

    {{-- Related Products Section --}}
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Loop through related products here --}}
             @foreach($relatedProducts as $relatedProduct)
                {{-- Display related product card (similar to products index page) --}}
                 <div class="group">
                    <div class="relative rounded-lg overflow-hidden">
                        @php
                            $imageUrl = $relatedProduct->images && is_array($relatedProduct->images) && !empty($relatedProduct->images) 
                                ? asset('storage/' . $relatedProduct->images[0]) 
                                : 'https://via.placeholder.com/400x300?text=' . urlencode($relatedProduct->name);
                        @endphp
                        <a href="{{ route('products.show', $relatedProduct->slug) }}">
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($relatedProduct->name) }}'">
                        </a>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">
                           <a href="{{ route('products.show', $relatedProduct->slug) }}" class="hover:underline">
                               {{ $relatedProduct->name }}
                           </a>
                       </h3>
                        {{-- Display category/subcategory for related product if needed --}}
                         <p class="mt-1 text-sm text-gray-500">
                            @php
                                $relatedCategoryName = $relatedProduct->category ? $relatedProduct->category->name : 'Uncategorized';
                                $relatedSubcategoryName = $relatedProduct->subcategory ? $relatedProduct->subcategory->name : '';
                            @endphp
                            {{ $relatedCategoryName }}
                            @if($relatedProduct->category && $relatedProduct->subcategory)
                                -
                            @endif
                            {{ $relatedSubcategoryName }}
                        </p>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-lg font-bold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</p>
                             {{-- Add to Cart button for related product if needed --}}
                             {{-- <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add to Cart</button> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
         @if($relatedProducts->isEmpty())
            <p class="text-gray-600">No related products found.</p>
        @endif
    </div>
</div>
@endsection
