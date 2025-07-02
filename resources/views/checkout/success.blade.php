@extends('layouts.app')

@section('title', 'Order Success')

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
        max-width: 600px;
        margin: 0 auto;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .success-card {
        background: white;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .success-icon {
        width: 64px;
        height: 64px;
        color: #10b981;
        margin: 0 auto 2rem;
    }

    .order-details {
        max-width: 400px;
        margin: 0 auto 2rem;
    }

    .details-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #4b5563;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-processing {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .primary-button {
        display: inline-block;
        background: #6366f1;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .primary-button:hover {
        background: #4f46e5;
        transform: translateY(-2px);
    }

    .secondary-button {
        display: inline-block;
        background: #f3f4f6;
        color: #4b5563;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .secondary-button:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .page-header {
            padding: 2rem 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .success-card {
            padding: 2rem 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .primary-button,
        .secondary-button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">
        @if($order->payment_method === 'cod')
            Order Placed Successfully!
        @else
            Payment Successful!
        @endif
    </h1>
    <p class="page-description">
        @if($order->payment_method === 'cod')
            Your order has been placed successfully. You can pay the amount when the order is delivered.
        @else
            Your payment has been processed successfully. Your order will be processed shortly.
        @endif
    </p>
</div>

<div class="container">
    <div class="success-card">
        <svg class="success-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>

        <div class="order-details">
            <h2 class="details-title">Order Details</h2>
            <div class="detail-item">
                <span class="detail-label">Order Number:</span>
                <span>{{ $order->order_number }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Subtotal:</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="detail-item">
                    <span class="detail-label">Discount:</span>
                    <span style="color: #059669; font-weight: 600;">-₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @if($order->coupon)
                    <div class="detail-item">
                        <span class="detail-label">Coupon Applied:</span>
                        <span style="color: #1d4ed8; font-weight: 600;">{{ $order->coupon->code }}</span>
                    </div>
                @endif
            @endif
            <div class="detail-item" style="border-top: 2px solid #1f2937; padding-top: 1rem; font-weight: 700; font-size: 1.1rem;">
                <span class="detail-label">Final Amount:</span>
                <span>₹{{ number_format($order->final_amount, 2) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Payment Method:</span>
                <span>
                    @if($order->payment_method === 'cod')
                        Cash on Delivery
                    @else
                        Online Payment (Razorpay)
                    @endif
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="status-badge 
                    @if($order->status === 'processing') status-processing
                    @elseif($order->status === 'completed') status-completed
                    @else status-pending
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('orders.index') }}" class="primary-button">
                View Order
            </a>
            <a href="{{ route('home') }}" class="secondary-button">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection 