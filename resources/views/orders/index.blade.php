@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <center><h1 class="text-3xl font-bold text-gray-800 mb-8">My Orders</h1>
</center>
        @if($orders->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg mb-6">You haven't placed any orders yet.</p>
                    <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    Start Shopping
                </a>
            </div>
        @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4">
    {{ $order->items->sum('quantity') }} item{{ $order->items->sum('quantity') > 1 ? 's' : '' }}
</td>
                       
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₹{{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->status === 'placed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'on_the_way') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'return_requested') bg-yellow-50 text-yellow-600
                                @elseif($order->status === 'return_approved') bg-green-50 text-green-600
                                @elseif($order->status === 'returned') bg-gray-100 text-gray-600
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition duration-200">View Details</a>
                            @if(method_exists($order, 'canBeCancelled') && $order->canBeCancelled())
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                   
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                        </tbody>
                    </table>
                                </div>

                @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
            </div>
    </div>
</div>
@endsection
