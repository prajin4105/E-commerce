@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                @if($order->payment_method === 'cod')
                    Order Placed Successfully!
                @else
                    Payment Successful!
                @endif
            </h1>

            <p class="text-gray-600 mb-6">
                @if($order->payment_method === 'cod')
                    Your order has been placed successfully. You can pay the amount when the order is delivered.
                @else
                    Your payment has been processed successfully. Your order will be processed shortly.
                @endif
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h2 class="text-lg font-semibold mb-4">Order Details</h2>
                <div class="space-y-2 text-left">
                    <p><span class="font-medium">Order Number:</span> {{ $order->order_number }}</p>
                    <p><span class="font-medium">Total Amount:</span> ₹{{ number_format($order->total_amount, 2) }}</p>
                    <p><span class="font-medium">Payment Method:</span> 
                        @if($order->payment_method === 'cod')
                            Cash on Delivery
                        @else
                            Online Payment (Razorpay)
                        @endif
                    </p>
                    <p><span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 text-sm rounded-full
                            @if($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('orders.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    View Order
                </a>
                <a href="{{ route('home') }}" class="inline-block ml-4 text-blue-600 hover:text-blue-800">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 