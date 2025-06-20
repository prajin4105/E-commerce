<style>
    .header {
        background: var(--bg-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 4rem;
    }

    .logo {
        display: flex;
        align-items: center;
    }

    .logo-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .logo-text:hover {
        color: var(--primary-hover);
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-link {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: color 0.2s ease;
        position: relative;
    }

    .nav-link:hover {
        color: var(--primary-color);
    }

    .cart-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cart-badge {
        background: var(--primary-color);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.125rem 0.375rem;
        border-radius: 9999px;
        min-width: 1.25rem;
        text-align: center;
    }

    .login-link {
        background: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .login-link:hover {
        background: var(--primary-hover);
        color: white;
        transform: translateY(-1px);
    }

    .logout-form {
        margin: 0;
    }

    .logout-button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font: inherit;
        color: inherit;
    }

    @media (max-width: 768px) {
        .header-content {
            height: 3.5rem;
        }

        .nav-links {
            gap: 1rem;
        }

        .logo-text {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 640px) {
        .container {
            padding: 0 1rem;
        }

        .nav-links {
            gap: 0.75rem;
        }

        .nav-link {
            font-size: 0.75rem;
        }

        .login-link {
            padding: 0.375rem 0.75rem;
        }
    }
</style>

<nav class="header">
<div class="container">
    <div class="header-content">
        <div class="logo">
            <a href="{{ url('/') }}" class="logo-text">Your Store</a>
        </div>
        <div class="nav-links">
            <a href="{{ url('/') }}" class="nav-link">Home</a>
            <a href="{{ url('/products') }}" class="nav-link">Products</a>
            <a href="{{ url('/categories') }}" class="nav-link">Categories</a>
            <a href="{{ url('/contact') }}" class="nav-link">Contact Us</a>

            <!-- Cart Link visible for all users -->
            <a href="{{ url('/cart') }}" class="nav-link cart-link">
                <i class="fas fa-shopping-cart"></i>
                @if(isset($cartCount) && $cartCount > 0)
                    <span class="cart-badge">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            @auth
                <a href="{{ url('/profile') }}" class="user-button" title="Profile">
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 mr-2">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
        </svg>
    </span>
</a>
            @else
                <a href="{{ url('/login') }}" class="nav-link login-link">Login/Register</a>
            @endauth
        </div>
    </div>
</div>
</nav>
