<x-mail::message>
# Order Confirmation

Hello {{ $order->user->name ?? $order->email }},

Thank you for your order! Your order <strong>#{{ $order->order_number }}</strong> has been placed successfully.

**Order Details:**

**Subtotal:** ₹{{ number_format($order->total_amount, 2) }}
@if($order->discount_amount > 0)
**Discount:** -₹{{ number_format($order->discount_amount, 2) }}
@if($order->coupon)
**Coupon Applied:** {{ $order->coupon->code }}
@endif
@endif
**Final Amount:** ₹{{ number_format($order->final_amount, 2) }}

<x-mail::button :url="url('/orders/' . $order->id)">
View Order
</x-mail::button>

We appreciate your business!<br>
{{ config('app.name') }}
</x-mail::message>
