@extends('layouts.app')

@section('title', 'Shopping Cart - Your E-Commerce Store')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if(empty($cart))
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700">Continue Shopping</a>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Remove</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cart as $productId => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    {{-- Product Image (optional) --}}
                                    {{-- <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="..." alt="">
                                    </div> --}}
                                    <div class="ml-0">
                                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                        {{-- Optional: Display product attributes like size, color --}}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item['price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <form action="{{ route('cart.update', $productId) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border rounded-md">
                                        <button type="button" class="px-3 py-1 text-gray-600 hover:text-gray-700 focus:outline-none" onclick="decrementQuantity(this)">-</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border-0 focus:ring-0" onchange="this.form.submit()">
                                        <button type="button" class="px-3 py-1 text-gray-600 hover:text-gray-700 focus:outline-none" onclick="incrementQuantity(this)">+</button>
                                    </div>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('cart.remove', $productId) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none p-0">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Cart Summary --}}
        <div class="mt-8 flex justify-end">
            <div class="w-full max-w-sm">
                {{-- Subtotal, Shipping, Total --}}
                <div class="text-lg font-bold text-gray-900 mb-4">
                    Subtotal: ${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}
                </div>
                {{-- Placeholder for Checkout button --}}
                <button class="mt-4 w-full bg-green-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-green-700">Proceed to Checkout</button>
            </div>
        </div>

    @endif
</div>
@endsection

@section('scripts')
<script>
    function incrementQuantity(button) {
        const input = button.parentElement.querySelector('input');
        input.value = parseInt(input.value) + 1;
        input.form.submit();
    }

    function decrementQuantity(button) {
        const input = button.parentElement.querySelector('input');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            input.form.submit();
        }
    }
</script>
@endsection 