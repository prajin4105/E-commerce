<x-guest-layout>
<style>
    .register-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .register-subtitle {
        font-size: 1rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .error-message {
        background-color: #fee2e2;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        color: #b91c1c;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-align: center;
    }
    .form-label {
        font-size: 1rem;
        color: #374151;
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    .form-input {
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
    .form-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
    }
    .submit-button {
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
    .submit-button:hover, .submit-button:focus {
        background: #4338ca;
        transform: translateY(-2px) scale(1.01);
    }
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .login-link a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .login-link a:hover {
        color: #4f46e5;
        text-decoration: underline;
    }
</style>
<div>
    <h2 class="register-title">Create Account</h2>
    <p class="register-subtitle">Join us and start shopping today</p>
    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label for="name" class="form-label">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus class="form-input @error('name') error @enderror" placeholder="Enter your full name">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-input @error('email') error @enderror" placeholder="Enter your email">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" class="form-input @error('password') error @enderror" placeholder="Create a password">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-input" placeholder="Confirm your password">
        <button type="submit" class="submit-button">Create Account</button>
        <div class="login-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </form>
</div>
</x-guest-layout>
