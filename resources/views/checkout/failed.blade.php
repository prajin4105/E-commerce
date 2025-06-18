@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="text-center">
            <div class="mb-4">
                <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Payment Failed</h1>
            <p class="text-gray-600 mb-6">
                We're sorry, but your payment could not be processed. This could be due to:
            </p>
            <ul class="text-left text-gray-600 mb-6 list-disc list-inside">
                <li>Insufficient funds in your account</li>
                <li>Payment was declined by your bank</li>
                <li>Technical issues with the payment gateway</li>
            </ul>
            <div class="space-y-4">
                <a href="{{ route('checkout.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Try Again
                </a>
                <div>
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 