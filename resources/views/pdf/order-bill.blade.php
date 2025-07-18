<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - Order #{{ $order->order_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #f8fafc;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px #e2e8f0;
            padding: 40px 40px 32px 40px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 18px;
            margin-bottom: 32px;
        }
        .brand {
            font-size: 2rem;
            font-weight: 600;
            color: #2563eb;
            letter-spacing: 1px;
        }
        .invoice-title {
            font-size: 1.3rem;
            color: #64748b;
            font-weight: 400;
        }
        .meta {
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .meta-block {
            margin-bottom: 8px;
        }
        .meta-label {
            color: #64748b;
            font-size: 0.97rem;
            font-weight: 500;
        }
        .meta-value {
            color: #1f2937;
            font-size: 1.05rem;
            font-weight: 600;
        }
        .addresses {
            display: flex;
            gap: 32px;
            margin-bottom: 32px;
        }
        .address-box {
            flex: 1;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 16px 20px;
        }
        .address-title {
            margin: 0 0 6px 0;
            font-size: 1rem;
            color: #2563eb;
            font-weight: 600;
        }
        .address-content {
            margin: 0;
            font-size: 0.97rem;
            color: #475569;
        }
        .customer-info {
            margin-bottom: 32px;
        }
        .customer-label {
            color: #64748b;
            font-size: 0.97rem;
            font-weight: 500;
        }
        .customer-value {
            color: #1f2937;
            font-size: 1.05rem;
            font-weight: 600;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 600;
            padding: 12px 8px;
            border-bottom: 2px solid #e5e7eb;
            text-align: left;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.98rem;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .totals {
            margin-top: 18px;
            width: 100%;
            max-width: 350px;
            float: right;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 1rem;
        }
        .totals-label {
            color: #64748b;
            font-weight: 500;
        }
        .totals-value {
            color: #1f2937;
            font-weight: 600;
        }
        .totals-row.total {
            border-top: 2px solid #2563eb;
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 8px;
        }
        .footer {
            margin-top: 48px;
            text-align: center;
            color: #64748b;
            font-size: 0.97rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 9999px;
            background: #dbeafe;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        .coupon-box {
            background: #f0f9ff;
            color: #1d4ed8;
            font-weight: 600;
            padding: 0.2rem 0.7rem;
            border-radius: 0.25rem;
            font-size: 0.95rem;
            margin-top: 6px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="brand">{{ config('app.name') }}</div>
            <div class="invoice-title">Invoice</div>
        </div>

        <div class="meta">
            <div class="meta-block">
                <span class="meta-label">Order #</span><br>
                <span class="meta-value">{{ $order->order_number }}</span>
            </div>
            <div class="meta-block">
                <span class="meta-label">Date</span><br>
                <span class="meta-value">{{ $order->created_at->format('M d, Y') }}</span>
            </div>
            <div class="meta-block">
                <span class="meta-label">Status</span><br>
                <span class="status-badge">
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
            </div>
        </div>

        <div class="addresses">
            <div class="address-box">
                <div class="address-title">Shipping Address</div>
                <div class="address-content">{{ $order->shipping_address }}</div>
            </div>
            <div class="address-box">
                <div class="address-title">Billing Address</div>
                <div class="address-content">{{ $order->billing_address }}</div>
            </div>
        </div>

        <div class="customer-info">
            <div><span class="customer-label">Customer:</span> <span class="customer-value">{{ $order->user->name ?? $order->email }}</span></div>
            <div><span class="customer-label">Email:</span> <span class="customer-value">{{ $order->email }}</span></div>
            <div><span class="customer-label">Phone:</span> <span class="customer-value">{{ $order->phone_number }}</span></div>
        </div>

        <table class="items-table">
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
                            <td>{{ $item->product->name ?? 'Product Not Found' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                        <td>{{ number_format($order->total_price / $order->quantity, 2) }}</td>
                        <td>{{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

        <div class="totals">
            <div class="totals-row">
                <span class="totals-label">Subtotal:</span>
                <span class="totals-value">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="totals-row">
                    <span class="totals-label">Discount:</span>
                    <span class="totals-value" style="color:#059669;">-₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @if($order->coupon)
                    <div class="totals-row">    
                        <span class="totals-label">Coupon Applied:</span>
                        <span class="coupon-box">{{ $order->coupon->code }}</span>
                    </div>
                @endif
            @endif
            <div class="totals-row total">
                <span class="totals-label">Total Amount:</span>
                <span class="totals-value">₹{{ number_format($order->final_amount, 2) }}</span>
        </div>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated document. No signature is required.</p>
        </div>
    </div>
</body> 
</html>
