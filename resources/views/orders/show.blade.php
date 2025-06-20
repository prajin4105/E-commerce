@extends('layouts.app')

@section('title', 'Order Details')

@section('styles')
<style>
    body {
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        background: #f3f4f6;
    }
    .order-container {
        min-height: 100vh;
        background-color: #f3f4f6;
        padding: 3rem 0.5rem 3rem 0.5rem;
    }
    .order-card {
        max-width: 48rem;
        margin: 0 auto;
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 8px 32px 0 rgba(31, 41, 55, 0.10);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    .order-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.25rem;
    }
    .order-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.5px;
    }
    .order-date {
        font-size: 1rem;
        color: #6b7280;
    }
    .status-badge {
        display: inline-block;
        padding: 0.35rem 1.1rem;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 9999px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.03);
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    .status-placed {
        background: linear-gradient(90deg, #dbeafe 60%, #e0e7ff 100%);
        color: #1d4ed8;
    }
    .status-on-the-way {
        background: linear-gradient(90deg, #fef9c3 60%, #fde68a 100%);
        color: #a16207;
    }
    .status-delivered {
        background: linear-gradient(90deg, #dcfce7 60%, #bbf7d0 100%);
        color: #166534;
    }
    .status-return-requested {
        background: linear-gradient(90deg, #fffbeb 60%, #fde68a 100%);
        color: #92400e;
    }
    .status-return-approved {
        background: linear-gradient(90deg, #f0fdf4 60%, #bbf7d0 100%);
        color: #166534;
    }
    .status-returned {
        background: linear-gradient(90deg, #f3f4f6 60%, #e5e7eb 100%);
        color: #4b5563;
    }
    .status-cancelled {
        background: linear-gradient(90deg, #fee2e2 60%, #fecaca 100%);
        color: #b91c1c;
    }
    .section-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.25rem;
        letter-spacing: -0.2px;
    }
    .item-card {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
        background: #f9fafb;
        padding: 1.1rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: box-shadow 0.2s;
    }
    .item-card:hover {
        box-shadow: 0 2px 8px 0 rgba(31, 41, 55, 0.08);
    }
    .item-image {
        height: 4.5rem;
        width: 4.5rem;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
    }
    .item-details {
        flex: 1;
    }
    .item-name {
        font-weight: 600;
        color: #111827;
        font-size: 1.05rem;
        margin-bottom: 0.2rem;
    }
    .item-meta {
        font-size: 0.93rem;
        color: #6b7280;
        margin-bottom: 0.1rem;
    }
    .total-amount {
        text-align: right;
        font-size: 1.18rem;
        font-weight: 700;
        margin-top: 1.2rem;
        color: #1f2937;
        letter-spacing: 0.2px;
    }
    .address-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2.2rem;
    }
    @media (min-width: 768px) {
        .address-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    .address-title {
        font-size: 0.98rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.3rem;
    }
    .address-content {
        font-size: 0.97rem;
        color: #374151;
        background: #f3f4f6;
        padding: 0.85rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    .contact-info {
        font-size: 0.97rem;
        color: #4b5563;
        margin-bottom: 2.2rem;
    }
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
        margin-top: 1.2rem;
    }
    .action-button {
        padding: 0.55rem 1.2rem;
        border-radius: 0.45rem;
        font-size: 0.97rem;
        font-weight: 600;
        transition: background 0.18s, color 0.18s, box-shadow 0.18s;
        border: none;
        cursor: pointer;
        box-shadow: 0 1px 2px 0 rgba(31,41,55,0.04);
    }
    .button-blue {
        background: linear-gradient(90deg, #dbeafe 60%, #e0e7ff 100%);
        color: #1d4ed8;
    }
    .button-blue:hover {
        background: linear-gradient(90deg, #bfdbfe 60%, #a5b4fc 100%);
        color: #1e40af;
    }
    .button-red {
        background: linear-gradient(90deg, #fee2e2 60%, #fecaca 100%);
        color: #b91c1c;
    }
    .button-red:hover {
        background: linear-gradient(90deg, #fecaca 60%, #fca5a5 100%);
        color: #991b1b;
    }
    .button-yellow {
        background: linear-gradient(90deg, #fef9c3 60%, #fde68a 100%);
        color: #a16207;
    }
    .button-yellow:hover {
        background: linear-gradient(90deg, #fde68a 60%, #fef08a 100%);
        color: #854d0e;
    }
    .button-green {
        background: linear-gradient(90deg, #dcfce7 60%, #bbf7d0 100%);
        color: #166534;
    }
    .button-green:hover {
        background: linear-gradient(90deg, #bbf7d0 60%, #86efac 100%);
        color: #14532d;
    }
    .button-gray {
        background: linear-gradient(90deg, #f3f4f6 60%, #e5e7eb 100%);
        color: #4b5563;
    }
    .button-gray:hover {
        background: linear-gradient(90deg, #e5e7eb 60%, #d1d5db 100%);
        color: #374151;
    }
    .back-link {
        font-size: 0.97rem;
        color: #6b7280;
        margin-top: 2.5rem;
        text-decoration: none;
        transition: color 0.18s;
        font-weight: 500;
    }
    .back-link:hover {
        color: #1f2937;
        text-decoration: underline;
    }
    @media (max-width: 600px) {
        .order-card {
            border-radius: 0.7rem;
            box-shadow: 0 2px 8px 0 rgba(31, 41, 55, 0.08);
        }
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .order-title {
            font-size: 1.15rem;
        }
        .order-date {
            font-size: 0.9rem;
        }
        .section-title {
            font-size: 1rem;
        }
        .item-card {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        .item-image {
            width: 100%;
            height: 8rem;
        }
        .address-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="order-container">
    <div class="order-card">
        <div class="px-6 py-8">
            <div class="order-header">
                <h1 class="order-title">Order #{{ $order->order_number }}</h1>
                <span class="order-date">Placed on {{ $order->created_at->format('M d, Y') }}</span>
            </div>

            <div class="mb-6">
                <span class="status-badge
                    @if($order->status === 'placed') status-placed
                    @elseif($order->status === 'on_the_way') status-on-the-way
                    @elseif($order->status === 'delivered') status-delivered
                    @elseif($order->status === 'return_requested') status-return-requested
                    @elseif($order->status === 'return_approved') status-return-approved
                    @elseif($order->status === 'returned') status-returned
                    @elseif($order->status === 'cancelled') status-cancelled
                    @endif">
                    {{
                        match($order->status) {
                            'placed' => 'Order Placed',
                            'on_the_way' => 'On the Way',
                            'delivered' => 'Delivered',
                            'return_requested' => 'Return Requested',
                            'return_approved' => 'Return Approved',
                            'returned' => 'Returned',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($order->status),
                        }
                    }}
                </span>
            </div>

            <div class="mb-8">
                <h2 class="section-title">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="item-card">
                            <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100/e2e8f0/1e293b?text=' . urlencode($item->product->name ?? 'Product') }}"
                                alt="{{ $item->product->name ?? 'Product' }}"
                                class="item-image">
                            <div class="item-details">
                                <p class="item-name">{{ $item->product->name ?? 'Product Not Found' }}</p>
                                <p class="item-meta">Qty: {{ $item->quantity }}</p>
                                <p class="item-meta">Price: ₹{{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="total-amount">Total: ₹{{ number_format($order->total_amount, 2) }}</div>
            </div>

            <div class="address-grid">
                <div>
                    <h3 class="address-title">Shipping Address</h3>
                    <p class="address-content">{{ $order->shipping_address }}</p>
                </div>
                <div>
                    <h3 class="address-title">Billing Address</h3>
                    <p class="address-content">{{ $order->billing_address }}</p>
                </div>
            </div>

            <div class="contact-info">
                <h3 class="address-title">Contact</h3>
                <p>Email: {{ $order->email }}</p>
                <p>Phone: {{ $order->phone_number }}</p>
            </div>

            <div class="action-buttons">
                @if(method_exists($order, 'canBeCancelled') && $order->canBeCancelled())
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="action-button button-red">Cancel Order</button>
                    </form>
                @endif
                @if($order->status === 'delivered')
                    <form action="{{ route('orders.send-bill', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-button button-blue">
                            Send Bill
                        </button>
                    </form>
                    <form action="{{ route('orders.request-return', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-button button-red">
                            Request Return
                        </button>
                    </form>
                @elseif($order->status === 'return_requested')
                    <span class="action-button button-yellow">Return Requested</span>
                @elseif($order->status === 'return_approved')
                    <span class="action-button button-green">Return Approved</span>
                @elseif($order->status === 'returned')
                    <span class="action-button button-gray">Returned</span>
                @endif
            </div>

            <div class="mt-10">
                <a href="{{ route('orders.index') }}" class="back-link">&larr; Back to My Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
