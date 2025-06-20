@extends('layouts.app')

@section('title', 'Home - Your E-Commerce Store')

@section('content')
<style>
    .hero-section {
        position: relative;
        height: 80vh;
        min-height: 600px;
        background: url('/images/hero-bg.jpg') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-bottom: 4rem;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(139, 92, 246, 0.9) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        padding: 0 2rem;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        letter-spacing: -0.025em;
        line-height: 1.1;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero-button {
        display: inline-block;
        background: white;
        color: #6366f1;
        padding: 1rem 2.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .hero-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .section {
        padding: 4rem 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1f2937;
        text-align: center;
        margin-bottom: 3rem;
        letter-spacing: -0.025em;
    }

    .grid {
        display: grid;
        gap: 2rem;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: stretch;
        height: auto;
    }

    .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        min-width: 220px;
        max-width: 260px;
        margin: 0 auto;
        padding-bottom: 1rem;
        height: 100%;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 16px 16px 0 0;
        margin-bottom: 1rem;
    }

    .card-content {
        width: 100%;
        padding: 0 1.2rem;
        text-align: center;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .card-description {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 1rem;
    }

    .card-button {
        display: inline-block;
        background: #6366f1;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .card-button:hover {
        background: #4f46e5;
        transform: translateY(-2px);
    }

    .cta-section {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 4rem 2rem;
        color: white;
        text-align: center;
        margin-top: 4rem;
    }

    .cta-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
    }

    .cta-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .cta-button {
        display: inline-block;
        background: white;
        color: #6366f1;
        padding: 1rem 2.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 3rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .cta-title {
            font-size: 2rem;
        }
    }

    @media (max-width: 640px) {
        .hero-section {
            height: 60vh;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }
    }

    .swiper-slide .card {
        border-radius: 16px !important;
        overflow: hidden;
        background: white;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Welcome to E-com</h1>
        <p class="hero-subtitle">Discover amazing products at unbeatable prices. Shop the latest trends and find your perfect style.</p>
        <a href="/products" class="hero-button">Shop Now</a>
    </div>
</div>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Featured Categories -->
<div class="section">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="swiper category-swiper">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                    <div class="swiper-slide">
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="min-w-[220px] flex-shrink-0 card hover:shadow-lg transition-shadow duration-300">
                            <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/500x300?text=' . urlencode($category->name) }}"
                                 alt="{{ $category->name }}"
                                 class="card-image"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/500x300?text={{ urlencode($category->name) }}'">
                            <div class="card-content">
                                <h3 class="card-title">{{ $category->name }}</h3>
                               
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="section bg-gray-50">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredProducts as $product)
                <div class="card">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : $product->getDefaultImageUrl() }}"
                         alt="{{ $product->name }}"
                         class="card-image"
                         onerror="this.onerror=null; this.src='{{ $product->getDefaultImageUrl() }}'">
                    <div class="card-content">
                        <h3 class="card-title">{{ $product->name }}</h3>
                        <p class="card-description">
                            @php
                                $categoryName = $product->category ? $product->category->name : 'Uncategorized';
                                $subcategoryName = $product->subcategory ? $product->subcategory->name : '';
                            @endphp
                            {{ $categoryName }}
                            @if($subcategoryName)
                                - {{ $subcategoryName }}
                            @endif
                        </p>
                        <p class="product-price">${{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="card-button">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="cta-section">
    <div class="cta-content">
        <div class="cta-text">
            <h2 class="cta-title">Ready to Shop?</h2>
            <p class="cta-subtitle">Browse our full collection of products and find your perfect match.</p>
        </div>
        <a href="/products" class="cta-button">Shop Now</a>
    </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.category-swiper', {
            slidesPerView: 3,
            spaceBetween: 24,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                0: { slidesPerView: 1 },
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });
    });
</script>
@endsection
