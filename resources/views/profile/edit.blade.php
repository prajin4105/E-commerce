@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<style>
    .profile-layout {
        display: flex;
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem;
        padding: 0 1rem;
    }

    .profile-sidebar {
        flex: 0 0 250px;
    }

    .profile-sidebar-nav {
        background-color: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .profile-sidebar-link {
        display: block;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: #374151;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .profile-sidebar-link:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    .profile-sidebar-link.active {
        background-color: #3b82f6;
        color: white;
        font-weight: 600;
    }

    .profile-content {
        flex: 1;
    }

    .profile-section {
        background-color: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                    0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .profile-section h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-section p {
        margin-bottom: 1.5rem;
    }

    .order-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: #1f2937;
        color: white;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 6px;
        transition: background-color 0.2s ease-in-out;
        text-decoration: none;
    }

    .order-button:hover {
        background-color: #374151;
    }

    .wishlist-grid {
        margin-top: 1.5rem;
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(4, 1fr);
    }

    .wishlist-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
        object-position: top;
        transition: transform 0.3s ease;
    }

    .wishlist-card-content {
        padding: 1rem;
    }

    .wishlist-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .wishlist-price {
        margin-top: 0.5rem;
        color: #4b5563;
    }

    .remove-button {
        margin-top: 1rem;
        padding: 0.5rem 1rem;
        background-color: #dc2626;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background-color 0.2s ease-in-out;
        width: 100%;
    }

    .remove-button:hover {
        background-color: #b91c1c;
    }
</style>

<div class="profile-layout" x-data="{ activeTab: 'profile' }">
    <aside class="profile-sidebar">
        <nav class="profile-sidebar-nav">
            <a @click="activeTab = 'profile'" :class="{ 'active': activeTab === 'profile' }" class="profile-sidebar-link">Profile Information</a>
            <a @click="activeTab = 'password'" :class="{ 'active': activeTab === 'password' }" class="profile-sidebar-link">Update Password</a>
            <a @click="activeTab = 'orders'" :class="{ 'active': activeTab === 'orders' }" class="profile-sidebar-link">My Orders</a>
            <a @click="activeTab = 'wishlist'" :class="{ 'active': activeTab === 'wishlist' }" class="profile-sidebar-link">Wish List</a>
            <a @click="activeTab = 'rated'" :class="{ 'active': activeTab === 'rated' }" class="profile-sidebar-link">Rated Products</a>
            <a @click="activeTab = 'delete'" :class="{ 'active': activeTab === 'delete' }" class="profile-sidebar-link">Delete Account</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 1.5rem;">
                @csrf
                <button type="submit" class="profile-sidebar-link" style="width:100%;text-align:left;color:#dc2626;font-weight:600;">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <main class="profile-content">
        <div x-show="activeTab === 'profile'" class="profile-section">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div x-show="activeTab === 'password'" class="profile-section">
            @include('profile.partials.update-password-form')
        </div>

        <div x-show="activeTab === 'orders'" class="profile-section">
            <h2>My Orders</h2>
            <p>View your order history and track your purchases.</p>
            <a href="{{ route('orders.index') }}" class="order-button">View My Orders</a>
        </div>

        <div x-show="activeTab === 'wishlist'" class="profile-section">
            <h2>My Wish List</h2>
            <p>Products you have saved to your wish list.</p>

            @if($wishlistItems->isEmpty())
                <p>Your wishlist is empty.</p>
            @else
                <div class="wishlist-grid">
                    @foreach($wishlistItems as $item)
                        <div class="wishlist-card">
                            <a href="{{ route('products.show', $item->product->id) }}" class="product-image-container">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-image">
                            </a>
                            <div class="wishlist-card-content">
                                <h3 class="wishlist-title">
                                    <a href="{{ route('products.show', $item->product->id) }}">{{ $item->product->name }}</a>
                                </h3>
                                <p class="wishlist-price">₹{{ number_format($item->product->price, 2) }}</p>
                                <form action="{{ route('wishlist.toggle', $item->product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="remove-button">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div x-show="activeTab === 'rated'" class="profile-section">
            <h2>My Rated Products</h2>
            <p>Products you have rated.</p>
            @if($userRatings->isEmpty())
                <p>You have not rated any products yet.</p>
            @else
                <div class="wishlist-grid">
                    @foreach($userRatings as $rating)
                        <div class="wishlist-card">
                            <a href="{{ route('products.show', $rating->product->id) }}" class="product-image-container">
                                <img src="{{ $rating->product->image_url }}" alt="{{ $rating->product->name }}" class="product-image">
                            </a>
                            <div class="wishlist-card-content">
                                <h3 class="wishlist-title">
                                    <a href="{{ route('products.show', $rating->product->id) }}">{{ $rating->product->name }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="wishlist-price">₹{{ number_format($rating->product->price, 2) }}</p>
                                <p class="text-gray-700 mt-2">{{ $rating->review }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div x-show="activeTab === 'delete'" class="profile-section">
            @include('profile.partials.delete-user-form')
        </div>
    </main>
</div>
@endsection
