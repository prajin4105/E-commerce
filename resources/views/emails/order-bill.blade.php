<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Bill</title>

</head>
<body>
    <div class="header">
        <h1>Order Bill</h1>
        <p>Order Number: #{{ $order->order_number }}</p>
        <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <div class="order-details">
        <h2>Order Details</h2>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
        <p><strong>Billing Address:</strong> {{ $order->billing_address }}</p>
    </div>

    <div class="product-list">
        <h2>Products</h2>
        @if($order->items->isNotEmpty())
            @foreach($order->items as $item)
                <div class="product-item">
                    <div>
                        <strong>{{ $item->product->name }}</strong>
                        <br>
                        Quantity: {{ $item->quantity }}
                    </div>
                    <div>
                        ₹{{ number_format($item->price, 2) }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="product-item">
                <div>
                    <strong>{{ $order->product->name }}</strong>
                    <br>
                    Quantity: {{ $order->quantity }}
                </div>
                <div>
                    ₹{{ number_format($order->total_price, 2) }}
                </div>
            </div>
        @endif
    </div>

    <div class="total">
        <p>Total Amount: ₹{{ number_format($order->total_price, 2) }}</p>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>If you have any questions, please contact our support team.</p>
    </div>
</body>
</html>
