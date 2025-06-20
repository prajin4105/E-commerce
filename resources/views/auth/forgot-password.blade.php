<x-guest-layout>
<style>
    .forgot-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .forgot-desc {
        font-size: 1rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .forgot-label {
        font-size: 1rem;
        color: #374151;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    .forgot-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1.1rem;
        color: #1f2937;
        background: #f9fafb;
        transition: border 0.2s, box-shadow 0.2s;
        outline: none;
        margin-bottom: 0.75rem;
    }
    .forgot-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
    }
    .forgot-btn {
        width: 100%;
        padding: 0.85rem 0;
        background: #6366f1;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
    }
    .forgot-btn:hover, .forgot-btn:focus {
        background: #4338ca;
        transform: translateY(-2px) scale(1.01);
    }
    .forgot-error {
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-align: center;
    }
    @media (max-width: 480px) {
        .forgot-title {
            font-size: 1.4rem;
        }
    }
</style>
<div>
    <h2 class="forgot-title">Forgot your password?</h2>
    <p class="forgot-desc">No problem. Enter your email and we'll send you a password reset link.</p>
    @if ($errors->any())
        <div class="forgot-error">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('status'))
        <div class="forgot-error" style="background:#d1fae5;color:#065f46;">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email" class="forgot-label">Email</label>
        <input id="email" class="forgot-input" type="email" name="email" value="{{ old('email') }}" required autofocus />
        <button type="submit" class="forgot-btn">Email Password Reset Link</button>
    </form>
</div>
</x-guest-layout>
