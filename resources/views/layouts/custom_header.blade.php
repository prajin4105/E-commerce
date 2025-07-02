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
        {{-- Show search bar only on products page --}}
        @if(Route::currentRouteName() === 'products.index')
        <!-- Autocomplete Search Form -->
        <div style="position: relative; display: flex; align-items: center; gap: 1rem;">
            <select id="search-category" style="padding: 0.5rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="text" id="search-input" placeholder="Search products..." autocomplete="off" style="padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; min-width: 180px; outline: none;" />
            <button id="search-btn" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
                <i class="fas fa-search"></i>
            </button>
            <div id="search-results" style="position: absolute; top: 110%; left: 0; right: 0; background: white; z-index: 1000; display: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px;"></div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('search-input');
            const category = document.getElementById('search-category');
            const results = document.getElementById('search-results');
            const searchBtn = document.getElementById('search-btn');
            let timeout = null;

            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = input.value;
                const cat = category.value;
                if (query.length < 2) {
                    results.style.display = 'none';
                    results.innerHTML = '';
                    return;
                }
                timeout = setTimeout(function() {
                    fetch(`/search/products?q=${encodeURIComponent(query)}&category=${cat}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length === 0) {
                                results.innerHTML = '<div style="padding:1rem;">No results found</div>';
                            } else {
                                results.innerHTML = data.map(product => `
                                    <a href="/products/${product.id}" style="display:flex;align-items:center;padding:0.5rem 1rem;text-decoration:none;color:#222;gap:1rem;">
                                        <img src="${product.image_url || 'https://via.placeholder.com/40x40'}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                        <div>
                                            <div><strong>${highlight(product.name, query)}</strong></div>
                                            <div style="color:#3b82f6;">₹${parseFloat(product.price).toFixed(2)}</div>
                                        </div>
                                    </a>
                                `).join('') + `<a href="/products?search=${encodeURIComponent(query)}" style="display:block;padding:0.75rem 1rem;text-align:center;color:#3b82f6;font-weight:500;">View all results</a>`;
                            }
                            results.style.display = 'block';
                        });
                }, 250);
            });

            // Submit on search button click
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const query = input.value;
                const cat = category.value;
                window.location.href = `/products?search=${encodeURIComponent(query)}${cat ? `&category=${cat}` : ''}`;
            });

            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!results.contains(e.target) && e.target !== input) {
                    results.style.display = 'none';
                }
            });

            // Highlight function
            function highlight(text, term) {
                if (!term) return text;
                return text.replace(new RegExp(`(${term})`, 'gi'), '<span style="background:yellow;">$1</span>');
            }
        });
        </script>
        @endif
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
