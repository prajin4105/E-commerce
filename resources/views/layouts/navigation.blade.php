<style>
    .nav-container {
        background: var(--bg-primary);
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .nav-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .nav-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 4rem;
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-logo {
        display: flex;
        align-items: center;
    }

    .nav-logo a {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .nav-logo svg {
        height: 2.25rem;
        width: auto;
    }

    .nav-links {
        display: flex;
        gap: 1.5rem;
    }

    .nav-link {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.5rem 0;
        position: relative;
        transition: color 0.2s ease;
    }

    .nav-link:hover {
        color: var(--primary-color);
    }

    .nav-link.active {
        color: var(--primary-color);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .auth-link {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .auth-link:hover {
        background: var(--bg-secondary);
    }

    .user-menu {
        position: relative;
    }

    .user-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        background: none;
        border: none;
        cursor: pointer;
        font: inherit;
        color: inherit;
        transition: background-color 0.2s ease;
    }

    .user-button:hover {
        background: var(--bg-secondary);
    }

    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.5rem;
        background: var(--bg-primary);
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        min-width: 12rem;
        overflow: hidden;
    }

    .dropdown-link {
        display: block;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.875rem;
        transition: background-color 0.2s ease;
    }

    .dropdown-link:hover {
        background: var(--bg-secondary);
    }

    .mobile-menu-button {
        display: none;
        padding: 0.5rem;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-primary);
    }

    .mobile-menu {
        display: none;
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .mobile-menu.active {
        display: block;
    }

    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .mobile-user-info {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .mobile-user-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .mobile-user-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    @media (max-width: 768px) {
        .nav-links {
            display: none;
        }

        .mobile-menu-button {
            display: block;
        }

        .nav-right {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .nav-content {
            padding: 0 1rem;
        }

        .nav-header {
            height: 3.5rem;
        }
    }
</style>

<nav x-data="{ open: false }" class="nav-container">
    <div class="nav-content">
        <div class="nav-header">
            <div class="nav-left">
                <div class="nav-logo">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="nav-links">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        {{ __('Dashboard') }}
                    </a>
                </div>
            </div>

            <div class="nav-right">
                @guest
                    <a href="{{ route('login') }}" class="auth-link">Login</a>
                @else
                    <div class="user-menu" x-data="{ open: false }">
                        <button @click="open = !open" class="user-button">
                            {{ Auth::user()->name }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false" 
                             class="user-dropdown">
                            <a href="{{ route('dashboard') }}" class="dropdown-link">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-link w-full text-left">Logout</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <button @click="open = !open" class="mobile-menu-button">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open }" 
                          class="inline-flex" 
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open, 'inline-flex': open }" 
                          class="hidden" 
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': !open}" class="mobile-menu">
        <div class="mobile-nav-links">
            <a href="{{ route('dashboard') }}" 
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                {{ __('Dashboard') }}
            </a>
        </div>

        @auth
            <div class="mobile-user-info">
                <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                <div class="mobile-user-email">{{ Auth::user()->email }}</div>
            </div>

            <div class="mobile-nav-links">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    {{ __('Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-full text-left">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>
