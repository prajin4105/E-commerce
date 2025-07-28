<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderBill;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderStatusChanged;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'items.product.category',
            'items.product.subcategory',
            'product.category',
            'product.subcategory'
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->simplePaginate(8);

        return view('orders.index', compact('orders'));
    }

    public function sendBill(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        try {
            // Estimate height
            $itemCount = $order->items->count();
            $baseHeight = 600;         // Base height for header, footer, etc.
            $perItemHeight = 25;       // Estimate for each item row
            $estimatedHeight = $baseHeight + ($itemCount * $perItemHeight);

            // Define limits
            $minHeight = 842;          // A4 height in points
            $maxHeight = 2500;         // Max limit to prevent overflow

            // Final height with padding
            $finalHeight = max($minHeight, min($estimatedHeight, $maxHeight)) + 300;

            // Generate PDF
            $pdf = PDF::loadView('pdf.order-bill', ['order' => $order])
                ->setPaper([0, 0, 595.28, $finalHeight], 'portrait'); // 595.28 = A4 width

            // Send email with PDF attachment
            Mail::to(Auth::user()->email)->send(new OrderBill($order, $pdf));

            return back()->with('success', 'Bill has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('PDF generation or mail sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send bill. Please try again later.');
        }
    }

    public function requestReturn(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'You can only request a return for delivered orders.');
        }

        if (in_array($order->status, ['return_requested', 'return_approved', 'returned'])) {
            return back()->with('error', 'Return already requested or completed.');
        }

        $order->status = 'return_requested';
        $order->save();

        Mail::to($order->email)->send(new OrderStatusChanged($order, 'Your return request has been received.'));

        return back()->with('success', 'Return request submitted successfully.');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load([
            'items.product.category',
            'items.product.subcategory',
            'product.category',
            'product.subcategory'
        ]);

        return view('orders.show', compact('order'));
    }
}
