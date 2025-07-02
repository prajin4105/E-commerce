@extends('layouts.app')

@section('title', 'Checkout')

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

    .checkout-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: #f9fafb;
    }

    .form-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #ffffff;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        min-height: 100px;
        resize: vertical;
        transition: all 0.2s ease;
        background-color: #f9fafb;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #ffffff;
    }

    .radio-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .radio-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 50%;
        cursor: pointer;
    }

    .radio-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .order-item-quantity {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .order-item-price {
        font-weight: 600;
        color: #1f2937;
    }

    .order-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 2px solid #e5e7eb;
    }

    .total-label {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }

    .total-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #6366f1;
    }

    .submit-button {
        display: block;
        width: 100%;
        background: #6366f1;
        color: white;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .submit-button:hover {
        background: #4f46e5;
        transform: translateY(-2px);
    }

    .submit-button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    .coupon-section {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .coupon-input-group {
        display: flex;
        gap: 1rem;
        align-items: end;
    }

    .coupon-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }

    .coupon-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .apply-coupon-btn {
        background: #10b981;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .apply-coupon-btn:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .apply-coupon-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    .coupon-message {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .coupon-success {
        color: #059669;
    }

    .coupon-error {
        color: #dc2626;
    }

    .discount-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
        color: #059669;
        font-weight: 600;
    }

    .discount-row:last-child {
        border-bottom: none;
    }

    .coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .coupon-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #0ea5e9;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .coupon-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15);
    }

    .coupon-card.selected {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-color: #10b981;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }

    .coupon-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #0ea5e9, #6366f1);
    }

    .coupon-card.selected::before {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .coupon-code {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .coupon-description {
        font-size: 0.875rem;
        color: #475569;
        margin-bottom: 0.75rem;
    }

    .coupon-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
        color: #64748b;
    }

    .coupon-discount {
        font-weight: 600;
        color: #059669;
    }

    .coupon-conditions {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.5rem;
    }

    .no-coupons {
        text-align: center;
        color: #666;
        font-style: italic;
    }

    .remove-coupon-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .remove-coupon-btn:hover {
        background-color: #c82333;
    }

    @media (max-width: 640px) {
        .page-header {
            padding: 2rem 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .checkout-section {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Checkout</h1>
    <p class="page-description">Complete your purchase</p>
</div>

<div class="container">
    <form id="checkout-form">
            @csrf
        <div class="checkout-section">
            <h2 class="section-title">Shipping Information</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required class="form-input">
                    </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" name="phone" id="phone" required class="form-input">
                </div>
                </div>
            </div>

        <div class="checkout-section">
            <h2 class="section-title">Address Information</h2>
            <div class="form-group">
                <label for="shipping_address" class="form-label">Shipping Address</label>
                <textarea name="shipping_address" id="shipping_address" required class="form-textarea"></textarea>
                    </div>
            <div class="form-group">
                <label for="billing_address" class="form-label">Billing Address</label>
                <textarea name="billing_address" id="billing_address" required class="form-textarea"></textarea>
                    </div>
                </div>

        <div class="checkout-section">
            <h2 class="section-title">Payment Method</h2>
            <div class="radio-group">
                <input type="radio" id="razorpay" name="payment_method" value="razorpay" checked class="radio-input">
                <label for="razorpay" class="radio-label">Pay Online (Razorpay)</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="cod" name="payment_method" value="cod" class="radio-input">
                <label for="cod" class="radio-label">Cash on Delivery</label>
            </div>
            </div>

        <div class="checkout-section">
            <h2 class="section-title">Coupon Code</h2>
           
            <div id="manual-coupon-message" class="coupon-message"></div>
            <div class="coupons-grid">
                @php
                $filteredCoupons = collect($allCouponsWithEligibility)->filter(function($item) {
                    return $item['message'] !== 'This coupon has expired.' && $item['message'] !== 'This coupon is not active.';
                });
                @endphp
                @forelse($filteredCoupons as $item)
                    @php $coupon = $item['coupon']; @endphp
                    <div class="coupon-card @if(!$item['eligible']) disabled-coupon @endif"
                         data-coupon-id="{{ $coupon->id }}"
                         data-coupon-code="{{ $coupon->code }}"
                         data-discount-type="{{ $coupon->discount_type }}"
                         data-discount-value="{{ $coupon->discount_value }}"
                         @if(!$item['eligible']) style="opacity:0.6;pointer-events:none;" @endif>
                        <div class="coupon-code">{{ $coupon->code }}</div>
                        <div class="coupon-description">
                            @if($coupon->discount_type === 'fixed')
                                Get ₹{{ number_format($coupon->discount_value, 2) }} off
                            @else
                                Get {{ $coupon->discount_value }}% off
                            @endif
                        </div>
                        <div class="coupon-details">
                            <span>Valid until: {{ $coupon->valid_to ? \Carbon\Carbon::parse($coupon->valid_to)->format('M d, Y') : 'No expiry' }}</span>
                            <span class="coupon-discount">
                                @if($coupon->discount_type === 'fixed')
                                    -₹{{ number_format($coupon->discount_value, 2) }}
                                @else
                                    -{{ $coupon->discount_value }}%
                                @endif
                            </span>
                        </div>
                        <div class="coupon-conditions">
                            @if($coupon->minimum_cart_value)
                                Min. cart: ₹{{ number_format($coupon->minimum_cart_value, 2) }}
                            @endif
                            @if($coupon->per_user_limit)
                                Per user: {{ $coupon->per_user_limit }}
                            @endif
                            @if($coupon->max_uses)
                                Usage limit: {{ $coupon->used_count }}/{{ $coupon->max_uses }}
                            @endif
                        </div>
                        @if(!$item['eligible'])
                            <div class="coupon-message coupon-error" style="margin-top:8px;">{{ $item['message'] }}</div>
                        @endif
                    </div>
                @empty
                    <div class="no-coupons">
                        <p>No coupons available at the moment.</p>
                    </div>
                @endforelse
            </div>
            <input type="hidden" name="selected_coupon_id" id="selected_coupon_id" value="">
            
            <!-- Remove Coupon Button -->
            <div id="remove-coupon-section" style="display: none; margin-top: 15px;">
                <button type="button" id="remove-coupon-btn" class="remove-coupon-btn">
                    Remove Applied Coupon
                </button>
            </div>
        </div>

        <div class="checkout-section">
            <h2 class="section-title">Order Summary</h2>
                    @foreach($cart as $item)
                <div class="order-item">
                            <div>
                        <h3 class="order-item-name">{{ $item['name'] }}</h3>
                        <p class="order-item-quantity">Quantity: {{ $item['quantity'] }}</p>
                    </div>
                    <p class="order-item-price">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                </div>
            @endforeach
            <div id="discount-row" class="discount-row" style="display: none;">
                <span>Discount:</span>
                <span id="discount-amount">-₹0.00</span>
            </div>
            <div class="order-total">
                <span class="total-label">Total Amount:</span>
                <span class="total-amount" id="final-total">₹{{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}</span>
                </div>
            </div>

        <button type="submit" id="pay-button" class="submit-button">
                    Place Order
                </button>
        </form>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// Coupon selection functionality
document.addEventListener('DOMContentLoaded', function() {
    const couponCards = document.querySelectorAll('.coupon-card');
    const selectedCouponInput = document.getElementById('selected_coupon_id');
    const discountRow = document.getElementById('discount-row');
    const discountAmount = document.getElementById('discount-amount');
    const finalTotal = document.getElementById('final-total');
    const removeCouponSection = document.getElementById('remove-coupon-section');
    const removeCouponBtn = document.getElementById('remove-coupon-btn');
    
    // Calculate initial total
    const initialTotal = {{ array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)) }};
    
    couponCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            couponCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Get coupon data
            const couponId = this.dataset.couponId;
            const couponCode = this.dataset.couponCode;
            const discountType = this.dataset.discountType;
            const discountValue = parseFloat(this.dataset.discountValue);
            
            // Set selected coupon
            selectedCouponInput.value = couponId;
            
            // Calculate discount
            let discount = 0;
            if (discountType === 'percent') {
                discount = (initialTotal * discountValue) / 100;
            } else {
                discount = discountValue;
            }
            
            // Update display
            discountRow.style.display = 'flex';
            discountAmount.textContent = `-₹${discount.toFixed(2)}`;
            const finalAmount = Math.max(0, initialTotal - discount);
            finalTotal.textContent = `₹${finalAmount.toFixed(2)}`;
            
            // Show remove coupon button
            removeCouponSection.style.display = 'block';
        });
    });
    
    // Remove coupon functionality
    removeCouponBtn.addEventListener('click', function() {
        // Remove selected class from all cards
        couponCards.forEach(c => c.classList.remove('selected'));
        
        // Clear selected coupon
        selectedCouponInput.value = '';
        
        // Hide discount row
        discountRow.style.display = 'none';
        
        // Reset total to initial amount
        finalTotal.textContent = `₹${initialTotal.toFixed(2)}`;
        
        // Hide remove coupon button
        removeCouponSection.style.display = 'none';
    });
});

document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const payButton = document.getElementById('pay-button');
    payButton.disabled = true;
    payButton.textContent = 'Processing...';

    const formData = new FormData(this);
    const paymentMethod = formData.get('payment_method');

    // Log form data for debugging
    console.log('Form data:', {
        email: formData.get('email'),
        phone: formData.get('phone'),
        shipping_address: formData.get('shipping_address'),
        billing_address: formData.get('billing_address'),
        payment_method: paymentMethod
    });

    try {
        const response = await fetch('{{ url("/checkout/process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: formData.get('email'),
                phone: formData.get('phone'),
                shipping_address: formData.get('shipping_address'),
                billing_address: formData.get('billing_address'),
                payment_method: paymentMethod,
                coupon_id: formData.get('selected_coupon_id')
            })
        });

        const data = await response.json();
        console.log('Server response:', data);  // Add this line for debugging
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to process order');
        }

        if (paymentMethod === 'cod') {
            window.location.href = `/checkout/success/${data.order_id}`;
        } else {
            const options = {
                key: '{{ config("services.razorpay.key") }}',
                amount: data.amount,
                currency: data.currency,
                name: '{{ config("app.name") }}',
                description: 'Order Payment',
                order_id: data.razorpay_order_id,
                handler: async function (response) {
                    try {
                        console.log('Razorpay payment response:', response);  // Log payment response
                        const paymentSuccessResponse = await fetch('{{ url("/checkout/payment-success") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                email: formData.get('email'),
                                phone: formData.get('phone'),
                                shipping_address: formData.get('shipping_address'),
                                billing_address: formData.get('billing_address')
                            })
                        });

                        const paymentData = await paymentSuccessResponse.json();
                        console.log('Payment success response:', paymentData);  // Log payment success response
                        if (!paymentData.success) {
                            throw new Error(paymentData.message || 'Failed to place order');
                        }

                        window.location.href = `/checkout/success/${paymentData.internal_order_id}`;
                    } catch (error) {
                        console.error('Payment error:', error);  // Log payment error
                        alert(error.message);
                        payButton.disabled = false;
                        payButton.textContent = 'Place Order';
                    }
                },
                prefill: {
                    email: formData.get('email'),
                    contact: formData.get('phone')
                },
                theme: {
                    color: '#4f46e5'
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }
    } catch (error) {
        alert(error.message);
        payButton.disabled = false;
        payButton.textContent = 'Place Order';
    }
});

// Manual coupon code application
const manualCouponInput = document.getElementById('manual-coupon-code');
const applyManualCouponBtn = document.getElementById('apply-manual-coupon-btn');
const manualCouponMessage = document.getElementById('manual-coupon-message');

applyManualCouponBtn.addEventListener('click', function() {
    const code = manualCouponInput.value.trim();
    if (!code) {
        manualCouponMessage.textContent = 'Please enter a coupon code.';
        manualCouponMessage.className = 'coupon-message coupon-error';
        return;
    }
    manualCouponMessage.textContent = 'Validating coupon...';
    manualCouponMessage.className = 'coupon-message';
    fetch('/checkout/validate-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon_code: code })
    })
    .then(async response => {
        let data;
        try {
            data = await response.json();
        } catch (e) {
            // Not JSON, likely a 419 or 500 error
            if (response.status === 419) {
                manualCouponMessage.textContent = 'Session expired. Please refresh the page and try again.';
            } else if (response.status === 500) {
                manualCouponMessage.textContent = 'Server error. Please check your coupon and try again.';
            } else {
                manualCouponMessage.textContent = 'Unexpected error. Please try again.';
            }
            manualCouponMessage.className = 'coupon-message coupon-error';
            console.error('Coupon validation error:', response);
            return;
        }
        if (data.success) {
            manualCouponMessage.textContent = data.message;
            manualCouponMessage.className = 'coupon-message coupon-success';
            // Set the selected coupon id and visually deselect all cards
            selectedCouponInput.value = data.coupon.id;
            couponCards.forEach(c => c.classList.remove('selected'));
            // Optionally, you can highlight the coupon if it's in the grid
            // Update discount row and final total
            discountRow.style.display = '';
            discountAmount.textContent = '-₹' + parseFloat(data.coupon.discount_amount).toFixed(2);
            finalTotal.textContent = '₹' + (initialTotal - parseFloat(data.coupon.discount_amount)).toFixed(2);
            removeCouponSection.style.display = '';
        } else {
            manualCouponMessage.textContent = data.message;
            manualCouponMessage.className = 'coupon-message coupon-error';
            // Remove expired/inactive coupon from grid if present
            const couponCard = Array.from(couponCards).find(c => c.dataset.couponCode === code);
            if (couponCard && (data.message.includes('expired') || data.message.includes('not active'))) {
                couponCard.remove();
            }
        }
    })
    .catch((error) => {
        manualCouponMessage.textContent = 'Failed to validate coupon. Please try again.';
        manualCouponMessage.className = 'coupon-message coupon-error';
        console.error('Coupon validation fetch error:', error);
    });
});
</script>
@endpush
@endsection
