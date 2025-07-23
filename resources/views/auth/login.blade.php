<x-guest-layout>
<style>
    .login-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .login-subtitle {
        font-size: 0.95rem;
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
    .info-text {
        text-align: center;
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 1rem;
        font-weight: 500;
    }
    .forgot-password-link {
        text-align: right;
        margin-bottom: 1rem;
    }
    .form-link {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.2s;
    }
    .form-link:hover {
        color: #4338ca;
        text-decoration: underline;
    }
    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .register-link a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .register-link a:hover {
        color: #4f46e5;
        text-decoration: underline;
    }
    </style>
    <div>
    <h2 class="login-title">Welcome Back</h2>
    <p class="login-subtitle">Enter your credentials to receive OTP</p>
    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <label for="email" class="form-label">Email Address</label>
        <input id="email" name="email" type="email" required class="form-input @error('email') error @enderror" placeholder="Enter your email" value="{{ old('email') }}">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" required class="form-input @error('password') error @enderror" placeholder="Enter your password">
        <div class="forgot-password-link">
            <a href="{{ route('password.request') }}" class="form-link">Forgot your password?</a>
        </div>
        <button type="submit" class="submit-button">Send OTP to Email</button>
        <p class="info-text">A verification code will be sent to your email after clicking the button above.</p>
    </form>
    <div class="register-link">
        <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
    <div style="text-align:center; margin-top:1.5rem;">
        <a href="{{ route('login.google') }}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 2rem;background:#fff;border:1.5px solid #d1d5db;border-radius:0.5rem;font-weight:600;color:#374151;text-decoration:none;box-shadow:0 2px 8px rgba(99,102,241,0.08);transition:background 0.2s;">
            <img src="https://img.icons8.com/?size=160&id=4hR4Ih04Je2t&format=png" alt="Google" style="width:22px;height:22px;"> Sign in with Google
        </a>
    </div>
</div>
</x-guest-layout>
