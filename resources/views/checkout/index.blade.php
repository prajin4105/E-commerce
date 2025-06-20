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
            <div class="order-total">
                <span class="total-label">Total Amount:</span>
                <span class="total-amount">₹{{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}</span>
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
                payment_method: paymentMethod
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
</script>
@endpush
@endsection
