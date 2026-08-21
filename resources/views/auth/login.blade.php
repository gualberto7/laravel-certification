@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
<div>
    <x-card>
        <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <h2 class="text-2xl font-bold mb-4 m-auto">Login</h2>

            <div>
                <label for="email">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    class="rounded-lg p-1 border border-gray-300">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    class="rounded-lg p-1 border border-gray-300">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input type="checkbox" name="remember" id="remember" class="rounded" value="1" @checked(old('remember'))>
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="text-sm bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded">
                Log in
            </button>
        </form>
    </x-card>
</div>
@endsection
