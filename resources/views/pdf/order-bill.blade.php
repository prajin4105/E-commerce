<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Soul Sky - Order #{{ $order->order_number }}</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #F5F5F3;
      font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
      font-size: 15px;
      margin: 0;
      padding: 0;
    }
    .invoice-container {
      width: 700px;
      margin: 30px auto;
      background: #fff;
      border-radius: 12px;
      padding: 0;
      border: 1px solid #e0e0e0;
      box-shadow: 0 6px 28px rgba(25,121,106,0.11);
    }
    .invoice-header {
      background: linear-gradient(90deg, #F9F6ED 70%, #d2ece6 100%);
      border-radius: 12px 12px 0 0;
      padding: 26px 36px 20px 36px;
    }
    .invoice-title {
      font-size: 2.25em;
      color: #19796A;
      font-weight: bold;
      letter-spacing: 2px;
      text-shadow: 0 2px 10px #e1f0ea;
    }
    .invoice-date {
      color: #555;
      font-size: 1.08em;
      margin-top: 8px;
      margin-bottom: 24px;
    }
    .info-table {
      width: 100%;
      margin-bottom: 20px;
    }
    .info-table td {
      vertical-align: top;
      padding: 0 10px 6px 0;
      font-size: 1.03em;
    }
    .info-label {
      color: #19796A;
      font-weight: bold;
      font-size: 1.09em;
      margin-bottom: 2px;
      display: block;
    }
    .info-content {
      color: #111;
    }
    .invoice-table-section {
      padding: 0 36px 0 36px;
    }
    table.invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 14px;
      background: #fff;
      border-radius: 10px 10px 0 0;
      overflow: hidden;
      box-shadow: 0 2px 14px rgba(25,121,106,0.04);
    }
    table.invoice-table th {
      background: #19796A;
      color: #fff;
      font-weight: bold;
      padding: 10px 4px;
      text-align: left;
      font-size: 1em;
      border-top: 1px solid #19796A;
      letter-spacing: 1px;
    }
    table.invoice-table td {
      color: #333;
      font-size: 1em;
      padding: 10px 4px;
      border-bottom: 1px solid #e6e6e6;
      background: #fcfdfa;
    }
    /* ---- ATTRACTIVE SUMMARY BOX ---- */
    .invoice-summary {
      width: 370px;
      margin: 30px 0 0 auto;
      border-radius: 14px;
      background: linear-gradient(120deg, #e3faf5 60%, #d2ece6 100%);
      box-shadow: 0 2px 24px rgba(25,121,106,0.13);
      overflow: hidden;
      font-size: 1.09em;
    }
    .invoice-summary-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    .invoice-summary-table td {
      padding: 15px 18px 10px 22px;
      border-bottom: 1px solid #c8e6e0;
      color: #19796A;
      font-weight: 500;
    }
    .invoice-summary-table tr:last-child td {
      border-bottom: none;
      background: linear-gradient(90deg, #19796A 90%, #27a48c 100%);
      color: #fff;
      font-size: 1.22em;
      font-weight: bold;
      letter-spacing: 1px;
      position: relative;
      box-shadow: 0 2px 16px #e1f7ed55;
    }
    .invoice-summary-table tr:last-child td:first-child:before {
      content: "\f00c";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      color: #ffeec1;
      margin-right: 10px;
      font-size: 1.1em;
      vertical-align: middle;
    }
    .invoice-summary-table td.label {
      text-align: right;
      width: 57%;
    }
    .invoice-summary-table td.amount {
      text-align: right;
      width: 43%;
      padding-right: 30px;
    }
    .footer-block {
      background: #F9F6ED;
      padding: 26px 36px;
      font-size: 1em;
      color: #19796A;
      text-align: left;
      margin-top: 22px;
      border-top: 1px solid #e0e0e0;
      border-radius: 0 0 12px 12px;
    }
    .footer-company {
      font-weight: bold;
      font-size: 1.09em;
      color: #19796A;
      margin-bottom: 3px;
    }
    .footer-contact {
      color: #222;
      font-size: 1em;
      line-height: 1.5;
    }
    @media print {
      .invoice-container { box-shadow: none; border: none; }
      .invoice-summary { box-shadow: none; }
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <div class="invoice-header">
      <div class="invoice-title">SOUL SKY</div>
      <div class="invoice-date">
        Issued: {{ $order->created_at->format('M d, Y') }}
      </div>
      <table class="info-table">
        <tr>
          <td width="48%">
            <span class="info-label">Bill to:</span>
            <div class="info-content">
              {{ $order->user->name ?? $order->email }}<br>
              {{ $order->shipping_address ?? 'N/A' }}
            </div>
          </td>
          <td width="4%"></td>
          <td width="48%">
            <span class="info-label">Payable to:</span>
            <div class="info-content">
              Soul Sky<br>
              hello@reallygreatsite.com<br>
              reallygreatsite.com
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div class="invoice-table-section">
      <table class="invoice-table">
        <thead>
          <tr>
            <th width="48%">Product</th>
            <th width="16%">Quantity</th>
            <th width="18%">Price</th>
            <th width="18%">Total</th>
          </tr>
        </thead>
        <tbody>
          @if($order->items->isNotEmpty())
            @foreach($order->items as $item)
              <tr>
                <td>{{ $item->product->name ?? 'Product Not Found' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
              </tr>
            @endforeach
          @else
            <tr>
              <td>{{ $order->product->name ?? 'Product Not Found' }}</td>
              <td>{{ $order->quantity }}</td>
              <td>{{ number_format($order->total_price / $order->quantity, 2) }}</td>
              <td>{{ number_format($order->total_price, 2) }}</td>
            </tr>
          @endif
        </tbody>
      </table>

      <!-- ATTRACTIVE SUMMARY BOX -->
      <div class="invoice-summary">
        <table class="invoice-summary-table">
          <tr>
            <td class="label">Subtotal:</td>
            <td class="amount">
              {{ number_format($order->total_amount, 2) }}
            </td>
          </tr>
          @if($order->discount_amount > 0)
          <tr>
            <td class="label">Discount:</td>
            <td class="amount">
              -{{ number_format($order->discount_amount, 2) }}
            </td>
          </tr>
          @endif
          <tr>
            <td class="label" style="color:#19796A;">Total Amount:</td>
            <td class="amount" style="color:#19796A;" >
              {{ number_format($order->final_amount, 2) }}
            </td>
          </tr>
        </table>
      </div>
    </div>
    <br>
    <div class="footer-block">
      <div class="footer-company">Soul Sky</div>
      <div class="footer-contact">
        23 Anywhere St., Any City, ST 123456<br>
        +91-123-456-7890<br>
        hello@reallygreatsite.com<br>
        reallygreatsite.com
      </div>
      <div style="margin-top: 12px; color: #999; font-size: 0.97em;">
        Thank you for your business!<br>
        This is a computer-generated document. No signature is required.
      </div>
    </div>
  </div>
</body>
</html>
