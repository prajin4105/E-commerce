@extends('layouts.app')

@section('title', $product->name . ' - Your E-Commerce Store')

@section('content')
<style>
    .product-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }
    .product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }
    .product-image {
        width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .product-details {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .price-box {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .price {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }
    .info-label {
        color: #6b7280;
        font-weight: 500;
    }
    .info-item a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .info-item a:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    .description-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }
    .description-text {
        color: #4b5563;
        line-height: 1.6;
        font-size: 0.875rem;
    }
    .add-to-cart-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.875rem 2rem;
        font-size: 1.05rem;
        background: #3b82f6;
        color: white;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.07);
        transition: all 0.22s cubic-bezier(0.4,0.2,0.3,1.5);
        margin-bottom: 0;
        width: 100%;
    }
    .add-to-cart-button.bg-green-500 {
        background: #22c55e;
        color: white;
    }
    .add-to-cart-button.bg-green-500:hover {
        background: #16a34a;
    }
    .add-to-cart-button.bg-blue-600:hover {
        background: #2563eb;
        transform: translateY(-2px) scale(1.03);
    }
    .related-section {
        margin-top: 4rem;
    }
    .related-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 2rem;
    }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
    }
    .related-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .related-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .related-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: top;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        transition: transform 0.3s ease;
    }
    .related-card:hover .related-image {
        transform: scale(1.05);
    }
    .related-content {
        padding: 1.25rem;
    }
    .related-product-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .related-product-category {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    .related-product-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
    }
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .product-container {
            padding: 1rem;
        }
        .price {
            font-size: 1.75rem;
        }
        .related-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
    }
</style>

<div class="product-container">
    <div class="product-grid">
        <!-- Product Images -->
        <div x-data="{
            images: @js(array_merge([$product->image_url], $product->gallery_urls)),
            current: 0,
            interval: null,
            start() {
                this.stop();
                this.interval = setInterval(() => {
                    this.next();
                }, 2000);
            },
            stop() {
                if (this.interval) clearInterval(this.interval);
            },
            next() {
                this.current = (this.current + 1) % this.images.length;
            },
            prev() {
                this.current = (this.current - 1 + this.images.length) % this.images.length;
            }
        }"
        x-init="start()"
        @mouseenter="stop()" @mouseleave="start()"
        class="relative product-slider-image-container" style="width:100%; min-width:320px;">
            <template x-if="images.length">
                <img 
                    :src="images[current]" 
                    alt="{{ $product->name }}" 
                    class="product-image mx-auto"
                    style="display:block;width:100%;height:350px;object-fit:contain;background:#f4f4f4;border-radius:12px;"
                    @click="next()"
                >
            </template>
            <template x-if="images.length > 1">
                <div class="flex justify-center mt-2 space-x-2">
                    <template x-for="(img, idx) in images" :key="idx">
                        <span @click="current = idx" :class="{'bg-blue-500': current === idx, 'bg-gray-300': current !== idx}" class="w-3 h-3 rounded-full inline-block cursor-pointer"></span>
                    </template>
                </div>
            </template>
        </div>

        <!-- Product Details -->
        <div class="product-details">
            <div class="price-box">
                <p class="price">₹{{ number_format($product->price, 2) }}</p>
                @if($product->stock <= 5 && $product->stock > 0)
                    <div style="color: #eab308; font-weight: bold; font-size: 1em; margin-bottom: 0.5rem;">Only {{ $product->stock }} left in stock!</div>
                @endif
                @if($product->stock == 0)
                    <div style="color: #ef4444; font-weight: bold; font-size: 1em; margin-bottom: 0.5rem;">Out of stock! This item cannot be ordered right now.</div>
                @endif

                <div class="info-item">
                    <span class="info-label">Category:</span>
                    <a href="{{ route('products.index', ['category' => $product->category->id]) }}">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </a>
                </div>
                @if($product->subcategory && $product->subcategory->name)
                    <div class="info-item">
                        <span class="info-label">Subcategory:</span>
                        <a href="{{ route('products.index', ['subcategory' => $product->subcategory->id]) }}">
                            {{ $product->subcategory->name }}
                        </a>
                    </div>
                @endif
                @if($product->stock !== null)
                    <div class="info-item">
                        <span class="info-label">Availability:</span>
                        @if($product->stock > 0)
                            In Stock ({{ $product->stock }} items)
                        @else
                            Out of Stock
                        @endif
                    </div>
                @endif
            </div>
            <div class="description-section">
                <h2 class="section-title">Description</h2>
                <p class="description-text">{{ $product->description }}</p>
            </div>

            {{-- BUTTONS SECTION --}}
            @if($product->stock > 0)
                <div class="flex items-center gap-4 w-full mt-2">
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="add-to-cart-button bg-blue-600 hover:bg-blue-700 transition duration-300 shadow font-semibold"
                            style="border-radius: 10px;">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                    </form>
                    <form action="{{ route('cart.buyNow') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                            class="add-to-cart-button bg-green-500 hover:bg-green-600 transition duration-300 shadow font-semibold"
                            style="border-radius: 10px;">
                            <i class="fas fa-bolt mr-2"></i> Buy Now
                        </button>
                    </form>
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="wishlist-button" style="background: white; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.07);">
                            @if(isset($isInWishlist) && $isInWishlist)
                                <i class="fas fa-heart" style="color: #ef4444; font-size: 1.5rem;"></i>
                            @else
                                <i class="far fa-heart" style="color: #6b7280; font-size: 1.5rem;"></i>
                            @endif
                        </button>
                    </form>
                </div>
            @else
                <p class="text-red-500 font-bold text-lg">Out of stock</p>
            @endif
        </div>
    </div>

    <!-- Reviews and Ratings Section -->
    <div class="reviews-section bg-white p-6 rounded-lg shadow-md mt-8">
        <h2 class="text-2xl font-bold mb-4">Customer Reviews</h2>
        <div class="flex items-center mb-6">
            @php
                $avgRating = $product->ratings->avg('rating');
                $ratingCount = $product->ratings->count();
            @endphp
            <div class="mr-4">
                <span class="text-4xl font-bold">{{ number_format($avgRating, 1) }}</span>
                <span class="text-gray-500">out of 5</span>
            </div>
            <div>
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <p class="text-sm text-gray-500">Based on {{ $ratingCount }} reviews</p>
            </div>
        </div>
        <div class="space-y-6">
            @forelse ($product->ratings()->latest()->get() as $rating)
                <div class="border-t pt-4">
                    <div class="flex items-center mb-2">
                        <div class="font-bold mr-2">{{ $rating->user->name }}</div>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700">{{ $rating->review }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $rating->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p>No reviews yet. Be the first to review this product!</p>
            @endforelse
        </div>
        <div class="mt-8 border-t pt-6">
            @auth
                @php
                    $userHasPurchased = Auth::user()->orders()->whereHas('items', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })->where('status', 'delivered')->exists();
                @endphp
                @if ($userHasPurchased)
                    @php
                        $userHasReviewed = Auth::user()->ratings()->where('product_id', $product->id)->exists();
                    @endphp
                    @if (!$userHasReviewed)
                        <h3 class="text-xl font-bold mb-4">Write a review</h3>
                        <form action="{{ route('products.ratings.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="rating" class="block font-semibold mb-2">Your Rating</label>
                                <div class="flex items-center review-form-stars">
                                    <input type="radio" name="rating" id="rating-1" value="1" class="hidden" required>
                                    <label for="rating-1"><i class="far fa-star text-2xl cursor-pointer"></i></label>
                                    <input type="radio" name="rating" id="rating-2" value="2" class="hidden">
                                    <label for="rating-2"><i class="far fa-star text-2xl cursor-pointer"></i></label>
                                    <input type="radio" name="rating" id="rating-3" value="3" class="hidden">
                                    <label for="rating-3"><i class="far fa-star text-2xl cursor-pointer"></i></label>
                                    <input type="radio" name="rating" id="rating-4" value="4" class="hidden">
                                    <label for="rating-4"><i class="far fa-star text-2xl cursor-pointer"></i></label>
                                    <input type="radio" name="rating" id="rating-5" value="5" class="hidden">
                                    <label for="rating-5"><i class="far fa-star text-2xl cursor-pointer"></i></label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="review" class="block font-semibold mb-2">Your Review</label>
                                <textarea name="review" id="review" rows="4" class="w-full border rounded p-2" placeholder="Share your thoughts on this product..."></textarea>
                            </div>
                            <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded hover:bg-green-600">Submit Review</button>
                        </form>
                    @else
                        <p class="text-green-600 font-semibold">You've already reviewed this product. Thank you!</p>
                    @endif
                @else
                    <p class="text-gray-600">You must purchase this product to leave a review.</p>
                @endif
            @else   
                <p><a href="{{ route('login') }}" class="text-blue-500 hover:underline">Log in</a> to leave a review.</p>
            @endauth
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="related-section">
            <h2 class="related-title">Related Products</h2>
            <div class="related-grid">
                @foreach($relatedProducts as $relatedProduct)
                    <a href="{{ route('products.show', $relatedProduct->id) }}" class="related-card">
                        @if($relatedProduct->image)
                        <img src="{{ $relatedProduct->image_url }}"
     alt="{{ $relatedProduct->name }}"
     class="related-image"
     style="width:100%;height:200px;object-fit:contain;background:#f4f4f4;"
     onerror="this.onerror=null; this.src='https://via.placeholder.com/500x300?text={{ urlencode($relatedProduct->name) }}'">

     @else
                            <img src="https://via.placeholder.com/500x300?text={{ urlencode($relatedProduct->name) }}"
                                 alt="{{ $relatedProduct->name }}"
                                 class="related-image">
                        @endif
                        <div class="related-content">
                            <h3 class="related-product-title">{{ $relatedProduct->name }}</h3>
                            <p class="related-product-category">
                                {{ $relatedProduct->category->name ?? 'Uncategorized' }}
                                @if($relatedProduct->subcategory && $relatedProduct->subcategory->name)
                                    - {{ $relatedProduct->subcategory->name }}
                                @endif
                            </p>
                            <p class="related-product-price">₹{{ number_format($relatedProduct->price, 2) }}</p>
                            @if($relatedProduct->stock <= 5 && $relatedProduct->stock > 0)
                                <div style="color: #eab308; font-weight: bold; font-size: 0.95em; margin-bottom: 0.25rem;">Only {{ $relatedProduct->stock }} left in stock!</div>
                            @endif
                            @if($relatedProduct->stock == 0)
                                <div style="color: #ef4444; font-weight: bold; font-size: 0.95em; margin-bottom: 0.25rem;">Out of stock!</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.review-form-stars label i');
        const starRadios = document.querySelectorAll('.review-form-stars input[type="radio"]');

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                // Highlight stars on hover
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.remove('far');
                    stars[i].classList.add('fas', 'text-yellow-400');
                }
                for (let i = index + 1; i < stars.length; i++) {
                    stars[i].classList.remove('fas', 'text-yellow-400');
                    stars[i].classList.add('far');
                }
            });

            star.addEventListener('mouseout', () => {
                // Return to selected state on mouseout
                updateStars();
            });

            star.addEventListener('click', () => {
                starRadios[index].checked = true;
                updateStars();
            });
        });

        function updateStars() {
            let selectedRating = 0;
            starRadios.forEach((radio, index) => {
                if (radio.checked) {
                    selectedRating = index + 1;
                }
            });

            stars.forEach((star, index) => {
                if (index < selectedRating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'text-yellow-400');
                } else {
                    star.classList.remove('fas', 'text-yellow-400');
                    star.classList.add('far');
                }
            });
        }
    });
</script>
@endpush
