@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 3rem 2rem;
        color: white;
        text-align: center;
        margin-bottom: 2rem;
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
    }

    .dashboard-description {
        font-size: 1.125rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .dashboard-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .card-content {
        color: #6b7280;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .card-link {
        display: inline-block;
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .card-link:hover {
        color: #4f46e5;
        transform: translateX(5px);
    }

    .logout-button {
        background: #ef4444;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .logout-button:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .dashboard-header {
            padding: 2rem 1rem;
        }

        .dashboard-title {
            font-size: 2rem;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-header">
    <h1 class="dashboard-title">Welcome to Your Dashboard</h1>
    <p class="dashboard-description">Manage your account, view orders, and track your shopping activity.</p>
</div>

<div class="container">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2 class="card-title">My Orders</h2>
            <p class="card-content">View and track your order history</p>
            <a href="{{ url('/orders') }}" class="card-link">View Orders →</a>
        </div>

        <div class="dashboard-card">
            <h2 class="card-title">Shopping Cart</h2>
            <p class="card-content">Review items in your cart</p>
            <a href="{{ url('/cart') }}" class="card-link">View Cart →</a>
        </div>

        <div class="dashboard-card">
            <h2 class="card-title">Account Settings</h2>
            <p class="card-content">Update your profile and preferences</p>
            <a href="{{ url('/profile') }}" class="card-link">Edit Profile →</a>
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="text-center">
        @csrf
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>
@endsection
