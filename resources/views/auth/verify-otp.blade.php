@extends('layouts.app')

@section('content')
<style>
    .otp-container {
        max-width: 400px;
        width: 100%;
        margin: 2rem auto;
        background: var(--bg-primary, #fff);
        padding: 2.5rem 2rem;
        border-radius: 1.25rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08), 0 1.5px 4px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .otp-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary, #1f2937);
        margin-bottom: 0.25rem;
    }
    .otp-subtitle {
        font-size: 1rem;
        color: var(--text-secondary, #6b7280);
        margin-bottom: 0.5rem;
    }
    .otp-error {
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }
    .otp-form label {
        font-size: 1rem;
        color: var(--text-primary, #1f2937);
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    .otp-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1.1rem;
        color: var(--text-primary, #1f2937);
        background: #f9fafb;
        transition: border 0.2s, box-shadow 0.2s;
        outline: none;
        margin-bottom: 0.5rem;
    }
    .otp-input:focus {
        border-color: var(--primary-color, #3b82f6);
        box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
    }
    .otp-input.error {
        border-color: #ef4444;
        background: #fef2f2;
    }
    .otp-btn {
        width: 100%;
        padding: 0.85rem 0;
        background: var(--primary-color, #3b82f6);
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        box-shadow: 0 2px 8px rgba(59,130,246,0.08);
    }
    .otp-btn:hover, .otp-btn:focus {
        background: var(--primary-hover, #2563eb);
        transform: translateY(-2px) scale(1.01);
    }
    .otp-resend {
        text-align: center;
        margin-top: 1.25rem;
        font-size: 0.98rem;
        color: var(--text-secondary, #6b7280);
    }
    .otp-resend a {
        color: var(--primary-color, #3b82f6);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }
    .otp-resend a:hover {
        color: var(--primary-hover, #2563eb);
        text-decoration: underline;
    }
    @media (max-width: 480px) {
        .otp-container {
            padding: 1.5rem 0.75rem;
        }
        .otp-title {
            font-size: 1.4rem;
        }
    }
</style>
<div class="otp-container">
    <!-- Logo and Title -->
    <div class="text-center">
        <h2 class="otp-title">Verify Your Email</h2>
        <p class="otp-subtitle">Please enter the OTP sent to your email</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="otp-error">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- OTP Form -->
    <form class="otp-form" method="POST" action="{{ route('verify.otp') }}">
        @csrf
        <div>
            <label for="otp">Enter OTP</label>
            <input id="otp" name="otp" type="text" required
                class="otp-input @error('otp') error @enderror"
                placeholder="Enter the OTP"
                maxlength="6">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="otp-btn">Verify OTP</button>

        <!-- Resend OTP Link -->
        <div class="otp-resend">
            Didn't receive the OTP? 
            <a href="{{ route('resend.otp') }}">Resend OTP</a>
        </div>
    </form>
</div>
@endsection 