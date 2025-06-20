<x-mail::message>
# Order Confirmation

Hello {{ $order->user->name ?? $order->email }},

Thank you for your order! Your order <strong>#{{ $order->order_number }}</strong> has been placed successfully.

**Order Total:** ₹{{ number_format($order->total_amount, 2) }}

<x-mail::button :url="url('/orders/' . $order->id)">
View Order
</x-mail::button>

We appreciate your business!<br>
{{ config('app.name') }}
</x-mail::message>
