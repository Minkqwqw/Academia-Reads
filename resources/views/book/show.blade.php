<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} - Academia Reads</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .text-navy { color: #1e3a8a; } /* Approximate navy */
        .text-emerald { color: #10b981; } /* Approximate emerald */
        .bg-navy { background-color: #1e3a8a; }
        .bg-emerald { background-color: #10b981; }
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

                    <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center">
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

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border p-6 md:p-10">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Cover -->
                <div class="md:w-1/3 flex-shrink-0">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-auto rounded shadow-md object-cover">
                    @else
                        <div class="w-full aspect-[2/3] bg-gray-200 flex items-center justify-center text-gray-500 rounded shadow-md text-xl">No Cover</div>
                    @endif
                </div>

                <!-- Detail -->
                <div class="md:w-2/3 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full uppercase font-bold tracking-wider mb-3">{{ $book->category->name }}</span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-navy mb-2">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-600 font-medium">By {{ $book->author }}</p>
                    </div>

                    <div class="mb-6 flex-grow">
                        <h2 class="text-lg font-bold text-gray-800 mb-2 border-b pb-2">Description</h2>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $book->description }}</div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <p class="text-3xl font-bold text-navy mb-1">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                            <p class="text-sm font-medium {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $book->stock > 0 ? 'In Stock: ' . $book->stock . ' available' : 'Out of Stock' }}
                            </p>
                        </div>

                        <div class="w-full md:w-auto">
                            @if($book->stock > 0)
                                <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $book->stock }}" class="w-20 rounded-lg border-gray-300 shadow-sm focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50 text-center text-lg py-2.5">
                                    <button type="submit" class="flex-grow md:flex-none bg-emerald text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-opacity-90 transition shadow-sm whitespace-nowrap">
                                        Masukkan ke Keranjang
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full md:w-auto bg-gray-300 text-gray-500 px-8 py-3 rounded-lg text-lg font-medium cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500 text-sm font-medium">&copy; {{ date('Y') }} Academia Reads. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
