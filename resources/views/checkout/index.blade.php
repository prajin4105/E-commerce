@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Checkout</h1>

        <form id="checkout-form" class="space-y-6">
            @csrf
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Shipping Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="tel" name="phone" id="phone" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Address Information</h2>
                <div class="space-y-4">
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                        <textarea name="billing_address" id="billing_address" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Payment Method</h2>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <input type="radio" id="razorpay" name="payment_method" value="razorpay" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="razorpay" class="block text-sm font-medium text-gray-700">
                            Pay Online (Razorpay)
                        </label>
                    </div>
                    <div class="flex items-center space-x-4">
                        <input type="radio" id="cod" name="payment_method" value="cod"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="cod" class="block text-sm font-medium text-gray-700">
                            Cash on Delivery
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                <div class="space-y-4">
                    @foreach($cart as $item)
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-600">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-medium">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                    @endforeach
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Amount:</span>
                            <span class="text-xl font-bold">₹{{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" id="pay-button" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Place Order
                </button>
            </div>
        </form>
    </div>
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

    try {
        if (paymentMethod === 'cod') {
            // Handle Cash on Delivery
            const response = await fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    shipping_address: formData.get('shipping_address'),
                    billing_address: formData.get('billing_address'),
                    payment_method: 'cod'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                window.location.href = `/checkout/success/${data.internal_order_id}`;
            } else {
                throw new Error(data.message || 'Failed to process order');
            }
        } else {
            // Handle Razorpay payment
            const response = await fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    shipping_address: formData.get('shipping_address'),
                    billing_address: formData.get('billing_address'),
                    payment_method: 'razorpay'
                })
            });

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to create payment order');
            }

            const options = {
                key: '{{ config("services.razorpay.key") }}',
                amount: data.amount,
                currency: data.currency,
                name: '{{ config("app.name") }}',
                description: 'Order Payment',
                order_id: data.razorpay_order_id,
                handler: function (response) {
                    window.location.href = `/checkout/success/${data.internal_order_id}?razorpay_payment_id=${response.razorpay_payment_id}&razorpay_order_id=${response.razorpay_order_id}&razorpay_signature=${response.razorpay_signature}`;
                },
                prefill: {
                    email: formData.get('email'),
                    contact: formData.get('phone')
                },
                theme: {
                    color: '#3B82F6'
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