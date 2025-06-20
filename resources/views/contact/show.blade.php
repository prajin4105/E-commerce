@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Contact Us</h1>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('contact.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" id="name" class="w-full border rounded p-2" required value="{{ old('name') }}">
            @error('name')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded p-2" required value="{{ old('email') }}">
            @error('email')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="message" class="block font-semibold mb-1">Message</label>
            <textarea name="message" id="message" rows="5" class="w-full border rounded p-2" required>{{ old('message') }}</textarea>
            @error('message')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700">Send Message</button>
    </form>
</div>
@endsection 