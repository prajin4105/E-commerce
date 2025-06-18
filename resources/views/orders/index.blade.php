@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Orders</h1>
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                Continue Shopping
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h2 class="text-lg font-semibold">Order #{{ $order->order_number }}</h2>
                                    <p class="text-sm text-gray-600">
                                        Placed on {{ $order->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Payment: 
                                        <span class="font-semibold
                                            @if($order->payment_status === 'paid') text-green-600
                                            @elseif($order->payment_status === 'failed') text-red-600
                                            @else text-gray-600
                                            @endif">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <h3 class="font-medium">{{ $item->product->name }}</h3>
                                                <p class="text-sm text-gray-600">
                                                    Quantity: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium">₹{{ number_format($item->quantity * $item->price, 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-t border-gray-200 mt-4 pt-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600">Shipping Address:</p>
                                            <p class="text-sm">{{ $order->shipping_address }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Total Amount:</p>
                                            <p class="text-lg font-semibold">₹{{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 