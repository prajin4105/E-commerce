<x-guest-layout>
<style>
    .reset-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .reset-desc {
        font-size: 1rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .reset-label {
        font-size: 1rem;
        color: #374151;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    .reset-input {
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
    .reset-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
    }
    .reset-btn {
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
    .reset-btn:hover, .reset-btn:focus {
        background: #4338ca;
        transform: translateY(-2px) scale(1.01);
    }
    .reset-error {
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-align: center;
    }
    .reset-success {
        background: #d1fae5;
        color: #065f46;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-align: center;
    }
    @media (max-width: 480px) {
        .reset-title {
            font-size: 1.4rem;
        }
    }
</style>
<div>
    <h2 class="reset-title">Reset your password</h2>
    <p class="reset-desc">Enter your email and new password below.</p>
    @if ($errors->any())
        <div class="reset-error">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('status'))
        <div class="reset-success">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <label for="email" class="reset-label">Email</label>
        <div class="reset-input" style="background:#f3f4f6;cursor:not-allowed;">{{ $request->email }}</div>
        <input type="hidden" name="email" value="{{ $request->email }}" />
        <label for="password" class="reset-label">Password</label>
        <input id="password" class="reset-input" type="password" name="password" required autocomplete="new-password" />
        <label for="password_confirmation" class="reset-label">Confirm Password</label>
        <input id="password_confirmation" class="reset-input" type="password" name="password_confirmation" required autocomplete="new-password" />
        <button type="submit" class="reset-btn">Reset Password</button>
    </form>
</div>
</x-guest-layout>
