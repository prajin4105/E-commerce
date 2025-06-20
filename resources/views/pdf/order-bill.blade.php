<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Bill</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; background: #f8fafc; color: #222; margin: 0; padding: 0; }
        .bill-container { max-width: 700px; margin: 32px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #e2e8f0; padding: 32px 32px 24px 32px; }
        .header { display: flex; align-items: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 18px; margin-bottom: 24px; }
        .logo { height: 48px; margin-right: 18px; }
        .company-info h2 { margin: 0 0 2px 0; font-size: 1.3rem; color: #2563eb; }
        .company-info p { margin: 0; font-size: 0.95rem; color: #64748b; }
        .order-meta { margin-bottom: 18px; }
        .order-meta th, .order-meta td { text-align: left; padding: 2px 8px 2px 0; font-size: 0.98rem; }
        .address-section { display: flex; gap: 32px; margin-bottom: 24px; }
        .address-box { flex: 1; background: #f1f5f9; border-radius: 8px; padding: 14px 18px; }
        .address-box h3 { margin: 0 0 6px 0; font-size: 1rem; color: #334155; }
        .address-box p { margin: 0; font-size: 0.97rem; color: #475569; }
        .product-list { margin-bottom: 24px; }
        .product-list h2 { font-size: 1.1rem; margin-bottom: 10px; color: #1e293b; }
        .product-table { width: 100%; border-collapse: collapse; background: #fff; }
        .product-table th { background: #f1f5f9; color: #334155; font-weight: 600; padding: 8px; border-bottom: 2px solid #e5e7eb; }
        .product-table td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 0.98rem; }
        .product-table tr:last-child td { border-bottom: none; }
        .total { text-align: right; font-size: 1.15rem; font-weight: bold; color: #1e293b; margin-top: 10px; }
        .footer { margin-top: 32px; text-align: center; color: #64748b; font-size: 0.97rem; }
    </style>
</head>
<body>
    <div class="bill-container">
        <div class="header">
            <!-- <img src="{{ public_path('logo.png') }}" alt="Logo" class="logo"> -->
            <div class="company-info">
                <h2>{{ config('app.name') }}</h2>
                <p>Order Number: #{{ $order->order_number }}</p>
                <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <table class="order-meta" style="margin-bottom: 18px;">
            <tr>
                <th>Order Status:</th>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            <tr>
                <th>Payment Method:</th>
                <td>{{ $order->payment_method }}</td>
            </tr>
        </table>

        <div class="address-section">
            <div class="address-box">
                <h3>Shipping Address</h3>
                <p>{{ $order->shipping_address }}</p>
            </div>
            <div class="address-box">
                <h3>Billing Address</h3>
                <p>{{ $order->billing_address }}</p>
            </div>
        </div>

        <div class="product-list">
            <h2>Order Items</h2>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if($order->items->isNotEmpty())
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>₹{{ number_format($order->total_price / $order->quantity, 2) }}</td>
                            <td>₹{{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="total">
            Total Amount: ₹{{ number_format($order->total_amount, 2) }}
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated document. No signature is required.</p>
        </div>
    </div>
</body>
</html>
