@extends('layouts.app')

@section('title', 'Categories - Your E-Commerce Store')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 3rem 2rem;
        color: white;
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
    }

    .page-description {
        font-size: 1.125rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .category-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .category-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.05);
    }

    .category-content {
        padding: 1.5rem;
    }

    .category-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .category-description {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    .subcategories-section {
        margin-bottom: 1.5rem;
    }

    .subcategories-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.75rem;
    }

    .subcategories-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .subcategory-tag {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .subcategory-tag:hover {
        background: #e5e7eb;
        color: #1f2937;
    }

    .view-products-button {
        display: inline-block;
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: #3b82f6;
        color: white;
        text-align: center;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .view-products-button:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .page-header {
            padding: 2rem 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .category-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .category-content {
            padding: 1.25rem;
        }

        .category-title {
            font-size: 1.25rem;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Shop by Category</h1>
    <p class="page-description">Browse our wide range of products by category</p>
</div>

<div class="container">
    <div class="category-grid">
        @foreach($categories as $category)
            <div class="category-card">
                <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/400x300?text=' . urlencode($category->name) }}"
                     alt="{{ $category->name }}"
                     class="category-image"
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($category->name) }}'">
                <div class="category-content">
                    <h2 class="category-title">{{ $category->name }}</h2>
                    <p class="category-description">{{ $category->description }}</p>

                                       <a href="{{ url('/products?category=' . $category->id) }}"
                       class="view-products-button">
                        View All Products
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
