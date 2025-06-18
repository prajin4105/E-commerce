<nav class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="text-xl font-bold">E-store</a>

        <!-- Navigation Links -->
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="hover:text-gray-300 px-3">Home</a>
            <a href="{{ route('products.index') }}" class="hover:text-gray-300 px-3">Products</a>
            <a href="{{ route('categories.index') }}" class="hover:text-gray-300 px-3">Categories</a>
            {{-- <a href="{{ url('/contact') }}" class="hover:text-gray-300 px-3">Contact</a> --}}
            <a href="{{ route('orders.index') }}" class="hover:text-gray-300 px-3">My Orders</a>
              <a href="{{ route('cart.index') }}" class="hover:text-gray-300 px-3 relative">
                Cart
                 @if(isset($cartCount) && $cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>
            {{-- Add more links here like Login/Signup --}}
             @guest
                <a href="{{ route('auth.combined') }}" class="hover:text-gray-300 px-3">Login</a>
                {{-- <a href="{{ route('auth.combined') }}" class="hover:text-gray-300 px-3">Sign Up</a> --}}
            @else
                 <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                    @csrf
                    <button type="submit" class="inline-block hover:text-gray-300 px-3 bg-transparent border-none p-0">Logout</button>
                </form>
            @endguest

            {{-- Cart Link with count --}}

        </div>
    </div>
</nav>
