@extends('layouts.app')

@section('title', 'Shopping Cart - Your E-Commerce Store')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .empty-cart-text {
        font-size: 1.25rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .continue-shopping-button {
        display: inline-block;
        background: #6366f1;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .continue-shopping-button:hover {
        background: #4f46e5;
        transform: translateY(-2px);
    }

    .cart-table {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .cart-header {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
        padding: 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .cart-header-item {
        font-weight: 600;
        color: #374151;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
        padding: 1.5rem;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .product-name {
        font-weight: 500;
        color: #1f2937;
    }

    .product-price {
        font-weight: 600;
        color: #1f2937;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-button {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quantity-button:hover {
        background: #e5e7eb;
    }

    .quantity-input {
        width: 60px;
        height: 32px;
        text-align: center;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-weight: 500;
    }

    .item-total {
        font-weight: 600;
        color: #1f2937;
    }

    .remove-button {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .remove-button:hover {
        background: #fee2e2;
    }

    .cart-summary {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .summary-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
    }

    .summary-total {
        font-weight: 700;
        color: #1f2937;
        font-size: 1.25rem;
        border-bottom: none;
        margin-top: 1rem;
    }

    .checkout-button {
        display: block;
        width: 100%;
        background: #6366f1;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        margin-top: 1.5rem;
        transition: all 0.2s ease;
    }

    .checkout-button:hover {
        background: #4f46e5;
        transform: translateY(-2px);
    }

    .continue-shopping-link {
        display: block;
        text-align: center;
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
        margin-top: 1rem;
        transition: all 0.2s ease;
    }

    .continue-shopping-link:hover {
        color: #4f46e5;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .cart-header {
            display: none;
        }

        .cart-item {
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 1rem;
        }

        .product-info {
            flex-direction: column;
            text-align: center;
        }

        .product-price, .item-total {
            text-align: center;
        }

        .quantity-control {
            justify-content: center;
        }

        .remove-button {
            margin: 0 auto;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Shopping Cart</h1>
    <p class="page-description">Review your items and proceed to checkout</p>
</div>

<div class="container">
    @if(empty($cart))
        <div class="empty-cart">
            <p class="empty-cart-text">Your cart is empty.</p>
            <a href="{{ url('/products') }}" class="continue-shopping-button">Continue Shopping</a>
        </div>
    @else
        <div class="cart-table">
            <div class="cart-header">
                <div class="cart-header-item">Product</div>
                <div class="cart-header-item">Price</div>
                <div class="cart-header-item">Quantity</div>
                <div class="cart-header-item">Total</div>
                <div class="cart-header-item"></div>
            </div>

                    @foreach($cart as $productId => $item)
                <div class="cart-item">
                    <div class="product-info">
                                    @if(isset($item['image']))
                            <img src="{{ $imageUrl }}"
                             alt="{{ $product->name }}"
                             class="product-image"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ $product->getDefaultImageUrl() }}'">
                                    @endif
                        <span class="product-name">{{ $item['name'] }}</span>
                                    </div>
                    <div class="product-price">${{ number_format($item['price'], 2) }}</div>
                    <div>
                        <form action="{{ url('/cart/update') }}" method="POST" class="update-quantity-form">
                                    @csrf
                            <div class="quantity-control">
                                <button type="button" class="quantity-button" onclick="decrementQuantity(this)">-</button>
                                <input type="number"
                                       name="quantity"
                                       value="{{ $item['quantity'] }}"
                                       min="1"
                                       max="{{ $item['stock'] ?? 1 }}"
                                       class="quantity-input"
                                       oninput="validateQuantityInput(this)"
                                       onchange="updateQuantity(this)">
                                <button type="button" class="quantity-button" onclick="incrementQuantity(this)">+</button>
                                    </div>
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                </form>
                    </div>
                    <div class="item-total">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                    <div>
                        <form action="{{ url('/cart/remove') }}" method="POST" class="remove-item-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                            <button type="submit" class="remove-button">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                                </form>
                    </div>
                </div>
                    @endforeach
        </div>

        <div class="cart-summary">
            <h2 class="summary-title">Order Summary</h2>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span>${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}</span>
                </div>

            <a href="{{ url('/checkout') }}" class="checkout-button">
                            Proceed to Checkout
                    </a>
            <a href="{{ url('/products') }}" class="continue-shopping-link">
                        Continue Shopping
                    </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function validateQuantityInput(input) {
        let min = parseInt(input.getAttribute('min'));
        let max = parseInt(input.getAttribute('max'));
        let val = parseInt(input.value);
        if (isNaN(val) || val < min) {
            input.value = min;
        } else if (val > max) {
            input.value = max;
        }
    }

    function incrementQuantity(button) {
        const input = button.parentElement.querySelector('input');
        const max = parseInt(input.getAttribute('max'));
        let val = parseInt(input.value);
        if (isNaN(val)) val = 1;
        if (val < max) {
            input.value = val + 1;
        updateQuantity(input);
        }
    }

    function decrementQuantity(button) {
        const input = button.parentElement.querySelector('input');
        const min = parseInt(input.getAttribute('min'));
        let val = parseInt(input.value);
        if (isNaN(val)) val = min;
        if (val > min) {
            input.value = val - 1;
            updateQuantity(input);
        }
    }

    function updateQuantity(input) {
        const form = input.closest('form');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: formData.get('product_id'),
                quantity: formData.get('quantity')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message || 'Failed to update cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update cart');
        });
    }

    // Handle remove item form submission
    document.querySelectorAll('.remove-item-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: formData.get('product_id')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to remove item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to remove item');
            });
        });
    });
</script>
@endsection
