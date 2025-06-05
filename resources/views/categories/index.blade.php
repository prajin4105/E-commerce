@extends('layouts.app')

@section('title', 'Categories - Your E-Commerce Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shop by Category</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="w-full h-48 bg-gray-200">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" 
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
                    
                    @if($category->subcategories->count() > 0)
                        <div class="mt-4">
                            <h3 class="text-lg font-medium mb-2">Subcategories:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($category->subcategories as $subcategory)
                                    <a href="{{ route('products.index', ['subcategory' => $subcategory->id]) }}" 
                                       class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                                        {{ $subcategory->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        View All Products
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 