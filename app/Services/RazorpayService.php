<?php

namespace App\Services;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Payment;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function createOrder(Order $order)
    {
        try {
            // Prepare order data
            $orderData = [
                'receipt'         => $order->order_number,
                'amount'          => (int)($order->total_amount * 100), // Convert to paise and ensure integer
                'currency'        => 'INR', // Explicitly set INR
                'payment_capture' => 1
            ];

            // Log order data for debugging
            \Log::info('Creating Razorpay order', [
                'order_data' => $orderData,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount
            ]);

            // Create Razorpay order
            $razorpayOrder = $this->api->order->create($orderData);

            // Log successful order creation
            \Log::info('Razorpay order created successfully', [
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $razorpayOrder->amount,
                'currency' => $razorpayOrder->currency
            ]);

            return $razorpayOrder;
        } catch (\Exception $e) {
            \Log::error('Failed to create Razorpay order', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'amount' => $order->total_amount
            ]);
            throw $e;
        }
    }

    public function verifyPayment($paymentId, $orderId, $signature)
    {
        try {
            // Basic validation
            if (empty($paymentId) || empty($orderId) || empty($signature)) {
                return false;
            }

            // Verify signature
            $attributes = [
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            
            // Get payment details
            $payment = $this->api->payment->fetch($paymentId);
            
            // Find the order
            $order = Order::where('razorpay_order_id', $orderId)->first();
            
            if (!$order) {
                return false;
            }

            // Create payment record
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_id' => $paymentId,
                    'payment_method' => 'razorpay',
                    'amount' => $payment->amount / 100,
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'transaction_id' => $orderId,
                    'payment_details' => [
                        'razorpay_payment_id' => $paymentId,
                        'razorpay_order_id' => $orderId,
                        'razorpay_signature' => $signature,
                        'bank' => $payment->bank ?? null,
                        'wallet' => $payment->wallet ?? null,
                        'vpa' => $payment->vpa ?? null,
                        'email' => $payment->email ?? null,
                        'contact' => $payment->contact ?? null,
                        'method' => $payment->method ?? null,
                    ],
                    'paid_at' => now(),
                ]
            );

            return true;
        } catch (\Exception $e) {
            // Create failed payment record if order exists
            if (isset($order)) {
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'payment_id' => $paymentId,
                        'payment_method' => 'razorpay',
                        'amount' => $order->total_amount,
                        'currency' => 'INR',
                        'status' => 'failed',
                        'transaction_id' => $orderId,
                        'payment_details' => [
                            'razorpay_payment_id' => $paymentId,
                            'razorpay_order_id' => $orderId,
                            'razorpay_signature' => $signature,
                            'error' => $e->getMessage(),
                        ],
                    ]
                );
            }
            
            return false;
        }
    }
} 