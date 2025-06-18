@extends('layouts.app')

@section('title', 'Sign Up - Your E-Commerce Store')

@section('content')
<div class="container mx-auto px-4 py-8 flex justify-center items-center">
    <div class="w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center">Sign Up</h1>

        <form action="{{ route('register') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email Address
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" id="password" type="password" name="password" required autocomplete="new-password">
                 @error('password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

             <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                    Confirm Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Sign Up
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
                    Already have an account? Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
