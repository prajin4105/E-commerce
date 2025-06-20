<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderBill;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderStatusChanged;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product.category', 'items.product.subcategory', 'product.category', 'product.subcategory'])
            ->where('user_id', Auth::id())
            ->latest()
            ->simplePaginate(8);

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function sendBill(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        try {
            // Generate PDF
            $pdf = PDF::loadView('pdf.order-bill', ['order' => $order]);
            
            // Send email with PDF attachment
            Mail::to(Auth::user()->email)->send(new OrderBill($order, $pdf));

            return back()->with('success', 'Bill has been sent to your email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send bill. Please try again later.');
        }
    }

    public function requestReturn(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        // Only allow return if delivered and not already returned/requested
        if ($order->status !== 'delivered') {
            return back()->with('error', 'You can only request a return for delivered orders.');
        }
        if (in_array($order->status, ['return_requested', 'return_approved', 'returned'])) {
            return back()->with('error', 'Return already requested or completed.');
        }

        $order->status = 'return_requested';
        $order->save();
        // Send return request email
        Mail::to($order->email)->send(new OrderStatusChanged($order, 'Your request for return has been received.'));

        return back()->with('success', 'Return request submitted successfully.');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load(['items.product.category', 'items.product.subcategory', 'product.category', 'product.subcategory']);
        return view('orders.show', compact('order'));
    }
} 