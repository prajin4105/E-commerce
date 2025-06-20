@extends('layouts.app')

@section('title', 'All Products - Your E-Commerce Store')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        margin-bottom: 2rem;
    }

    .breadcrumb-link {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-link:hover {
        color: #3b82f6;
    }

    .breadcrumb-separator {
        margin: 0 0.5rem;
        color: #9ca3af;
    }

    .breadcrumb-current {
        color: #3b82f6;
        font-weight: 500;
    }

    .page-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 2rem;
        letter-spacing: -0.025em;
    }

    .filters-container {
        background: white;
        padding: 1.5rem;
        b   -radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .filters-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .filter-select {
        padding: 0.625rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background-color: white;
        color: #4b5563;
        font-size: 0.875rem;
        min-width: 200px;
        transition: all 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .clear-filters {
        padding: 0.625rem 1.25rem;
        background: #6b7280;
        color: white;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .clear-filters:hover {
        background: #4b5563;
        transform: translateY(-1px);
    }

    .no-products {
        text-align: center;
        padding: 3rem 1rem;
    }

    .no-products-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .no-products-text {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .product-image-container {
        position: relative;
        padding-top: 100%;
    }

    .product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top; /* Always show the top of the image */
    transition: transform 0.3s ease;
}
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .wishlist-button {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: white;
        padding: 0.5rem;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .wishlist-button:hover {
        background: #f3f4f6;
        transform: scale(1.1);
    }

    .wishlist-icon {
        color: #6b7280;
        font-size: 1.25rem;
    }

    .product-content {
        padding: 1.25rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        text-decoration: none;
    }

    .product-title:hover {
        color: #3b82f6;
    }

    .product-category {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .product-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
    }

    .add-to-cart {
        padding: 0.5rem 1rem;
        background: #3b82f6;
        color: white;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-to-cart:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .pagination-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        color: #4b5563;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pagination-link:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .pagination-active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    @media (max-width: 640px) {
        .container {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.875rem;
        }

        .filters-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select {
            width: 100%;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .product-content {
            padding: 1rem;
        }
    }
</style>

<div class="container">
    <!-- Breadcrumb Navigation -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('categories.index') }}" class="breadcrumb-link">Categories</a>
            @if(request('category'))
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">
                    {{ $categories->firstWhere('id', request('category'))->name ?? 'Category' }}
            </span>
            @endif
            @if(request('subcategory'))
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">
                    {{ $subcategories->firstWhere('id', request('subcategory'))->name ?? 'Subcategory' }}
            </span>
            @endif
    </div>

    <h1 class="page-title">All Products</h1>

    <!-- Filters -->
    <div class="filters-container">
        <form id="filterForm" action="{{ route('products.index') }}" method="GET" class="filters-form">
            <select name="category" id="categorySelect" onchange="this.form.submit()" class="filter-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    @if($category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endif
                @endforeach
            </select>

            <select name="subcategory" id="subcategorySelect" onchange="this.form.submit()" class="filter-select">
                <option value="">All Subcategories</option>
                @if(request('category'))
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
                    @foreach($subcategories as $subcategory)
                        @if($subcategory)
                            <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>

            <select name="sort" onchange="this.form.submit()" class="filter-select">
                <option value="">Sort By</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
            </select>

            @if(request()->hasAny(['category', 'subcategory', 'sort']))
                <a href="{{ route('products.index') }}" class="clear-filters">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="no-products">
            <h3 class="no-products-title">No products found</h3>
            <p class="no-products-text">Try adjusting your filters or check back later for new products.</p>
        </div>
    @else
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image-container">
                        <a href="{{ route('products.show', $product->id) }}">
                            <img src="{{ $product->image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($product->name) }}'">
                        </a>
                       
                    </div>
                    <div class="product-content">
                        <h3>
                            <a href="{{ route('products.show', $product->id) }}" class="product-title">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="product-category">
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

                        <div class="flex items-center mb-4">
                            @php
                                $avgRating = $product->ratings->avg('rating');
                                $fullStars = floor($avgRating);
                                $halfStar = $avgRating - $fullStars >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor

                            @if ($halfStar)
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star text-yellow-400"></i>
                            @endfor

                            <span class="text-xs text-gray-500 ml-2">({{ $product->ratings->count() }} reviews)</span>
                        </div>

                        <div class="product-footer" style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; flex-direction: column; align-items: flex-start;">
                                <p class="product-price" style="margin-bottom: 0.25rem;">₹{{ number_format($product->price, 2) }}</p>
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span style="color: #eab308; font-weight: bold; font-size: 0.95em;">Only {{ $product->stock }} left in stock!</span>
                                @endif
                                @if($product->stock == 0)
                                    <span style="color: #ef4444; font-weight: bold; font-size: 0.95em;">Out of stock! This item cannot be ordered right now.</span>
                                @endif
                            </div>
                            @if($product->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST" style="margin-left: 1rem;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-to-cart">Add to Cart</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection 