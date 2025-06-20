@extends('layouts.app')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        padding: 3rem 2rem;
        color: white;
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
    }

    .page-description {
        font-size: 1.125rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .error-card {
        background: white;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .error-icon {
        width: 64px;
        height: 64px;
        color: #ef4444;
        margin: 0 auto 2rem;
    }

    .error-list {
        max-width: 400px;
        margin: 0 auto 2rem;
        text-align: left;
    }

    .error-list-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        color: #4b5563;
        font-size: 0.875rem;
    }

    .error-list-item::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .primary-button {
        display: inline-block;
        background: #ef4444;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .primary-button:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .secondary-button {
        display: inline-block;
        background: #f3f4f6;
        color: #4b5563;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .secondary-button:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .page-header {
            padding: 2rem 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .error-card {
            padding: 2rem 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .primary-button,
        .secondary-button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Payment Failed</h1>
    <p class="page-description">We're sorry, but your payment could not be processed.</p>
</div>

<div class="container">
    <div class="error-card">
        <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>

        <div class="error-list">
            <div class="error-list-item">Insufficient funds in your account</div>
            <div class="error-list-item">Payment was declined by your bank</div>
            <div class="error-list-item">Technical issues with the payment gateway</div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('checkout.index') }}" class="primary-button">
                Try Again
            </a>
            <a href="{{ route('home') }}" class="secondary-button">
                Return to Home
            </a>
        </div>
    </div>
</div>
@endsection
