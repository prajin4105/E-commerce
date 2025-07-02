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

    public function createOrder($orderId, $amount)
    {
        try {
            $order = \App\Models\Order::find($orderId);
            if (!$order) {
                \Log::error('Failed to create Razorpay order', [
                    'error' => 'Order not found',
                    'order_id' => $orderId,
                    'amount' => $amount
                ]);
                return false;
            }

            $orderData = [
                'receipt' => 'ORD-' . $order->id,
                'amount' => (int) round($amount * 100), // Convert to paise and ensure integer
                'currency' => 'INR',
                'payment_capture' => 1
            ];

            \Log::info('Creating Razorpay order', [
                'order_data' => $orderData,
                'order_id' => $orderId,
                'order_number' => $orderData['receipt'],
                'total_amount' => $amount
            ]);

            $razorpayOrder = $this->api->order->create($orderData);

            \Log::info('Razorpay order created successfully', [
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency']
            ]);

            return $razorpayOrder;
        } catch (\Exception $e) {
            \Log::error('Failed to create Razorpay order', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
                'amount' => $amount
            ]);
            return false;
        }
    }

    public function verifyPayment($paymentId, $orderId, $signature)
    {
        try {
            if (empty($paymentId) || empty($orderId) || empty($signature)) {
                \Log::error('Payment verification failed: Missing payment details', [
                    'payment_id' => $paymentId,
                    'order_id' => $orderId,
                    'signature' => $signature
                ]);
                return false;
            }

            $order = \App\Models\Order::where('razorpay_order_id', $orderId)->first();
            if (!$order) {
                \Log::error('Payment verification failed: Order not found', [
                    'razorpay_order_id' => $orderId
                ]);
                return false;
            }

            $attributes = [
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            \Log::error('Payment verification failed: ' . $e->getMessage(), [
                'razorpay_order_id' => $orderId
            ]);
            return false;
        }
    }
} 