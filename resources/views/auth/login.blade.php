<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Academia Reads</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="font-serif text-3xl font-bold text-navy mb-2">Academia Reads</h1>
            <p class="text-gray-600 font-sans">Login to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 font-sans mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded border-gray-300 shadow-sm focus:border-emerald focus:ring-emerald font-sans">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 font-sans">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 font-sans mb-1">Password</label>
                <input id="password" type="password" name="password" required class="w-full rounded border-gray-300 shadow-sm focus:border-emerald focus:ring-emerald font-sans">
                @error('password')
                    <span class="text-red-500 text-xs mt-1 font-sans">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-emerald focus:ring-emerald">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700 font-sans">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-emerald hover:underline font-sans">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="w-full bg-emerald text-white rounded py-2 px-4 font-sans font-medium hover:bg-opacity-90 transition">
                Log In
            </button>
            
            <div class="mt-4 text-center font-sans text-sm">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-emerald hover:underline font-medium">Register here</a>
            </div>
        </form>
    </div>
</body>
</html>
