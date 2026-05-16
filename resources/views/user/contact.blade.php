<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us - Academia Reads</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .text-navy { color: #1e3a8a; } /* Approximate navy */
        .text-emerald { color: #10b981; } /* Approximate emerald */
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-serif font-bold text-navy">Academia Reads</a>
            <nav class="flex gap-6 items-center font-medium text-sm">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald transition">Home</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-emerald transition">About Us</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-emerald transition">Contact</a>
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-emerald flex items-center gap-1 transition">
                    Cart
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="bg-red-500 text-white rounded-full px-2 py-0.5 text-xs font-bold">{{ count(session('cart')) }}</span>
                    @endif
                </a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-emerald transition">Dashboard Admin</a>
                    @else
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-emerald transition">My Orders</a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center transition-opacity hover:opacity-80" title="Go to Profile">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Photo" class="h-8 w-8 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm border border-gray-300">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline flex items-center">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald transition">Log in</a>
                    <a href="{{ route('register') }}" class="bg-navy text-white px-4 py-2 rounded hover:bg-opacity-90 transition">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <div class="bg-white rounded-xl shadow-sm border p-10 md:p-16">

            <div class="text-center mb-10">
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-navy mb-4">Contact Us</h1>
                <p class="text-gray-600 text-lg">Punya pertanyaan atau masukan? Jangan ragu untuk menghubungi kami.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('messages.store') }}" method="POST" class="max-w-2xl mx-auto">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50" placeholder="Masukkan nama Anda">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50" placeholder="Masukkan alamat email Anda">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subjek</label>
                    <input type="text" name="subject" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50" placeholder="Subjek pesan">
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pesan</label>
                    <textarea name="message" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50 h-40 resize-none" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."></textarea>
                </div>
                <button type="submit" class="w-full bg-navy text-white px-6 py-3 rounded-md font-bold hover:bg-opacity-90 transition text-lg shadow">Kirim Pesan</button>
            </form>

        </div>
    </main>

    <footer class="bg-white border-t mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500 text-sm font-medium">&copy; {{ date('Y') }} Academia Reads. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
