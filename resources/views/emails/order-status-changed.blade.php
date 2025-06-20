<x-mail::message>
# Order Update

Hello {{ $order->user->name ?? $order->email }},

Your order <strong>#{{ $order->order_number }}</strong>:

{{ $message }}

<x-mail::button :url="url('/orders/' . $order->id)">
View Order
</x-mail::button>

Thank you for shopping with us!<br>
{{ config('app.name') }}
</x-mail::message>
