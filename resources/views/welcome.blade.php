<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - Academia Reads</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #064E3B; /* Deep Emerald */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
        <div class="loader"></div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="opacity-0 transition-opacity duration-500 flex flex-col flex-grow">
        <!-- Navbar -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-serif font-bold text-navy">Academia Reads</a>
                <nav class="flex gap-6 items-center font-medium text-sm">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald transition">Home</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-emerald transition">About Us</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-emerald transition">Contact</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-emerald flex items-center gap-1 transition">                        Cart
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

        <!-- Hero Section -->
        <section class="bg-navy text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-serif font-bold mb-4">Discover Your Next Academic Treasure</h1>
                <p class="text-gray-300 mb-8 max-w-2xl mx-auto">Explore thousands of scholarly books, research materials, and academic literature carefully curated for you.</p>

                <!-- Search Bar -->
                <form action="{{ route('books.search') }}" method="GET" class="max-w-xl mx-auto flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="w-full rounded border-0 px-4 py-3 text-gray-900 focus:ring-emerald">
                    <button type="submit" class="bg-emerald text-white px-6 py-3 rounded font-medium hover:bg-opacity-90 transition whitespace-nowrap">Search</button>
                </form>
            </div>
        </section>

        <!-- Book Catalog -->
        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-serif font-bold text-navy">Latest Books</h2>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($books as $book)
                    <div class="bg-white rounded-lg shadow-sm border overflow-hidden flex flex-col hover:shadow-md transition">
                        <a href="{{ route('books.show', $book->id) }}" class="block">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-64 object-cover">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500 hover:opacity-90 transition">No Cover</div>
                            @endif
                        </a>
                        <div class="p-5 flex-grow flex flex-col">
                            <span class="text-xs font-medium text-emerald mb-2 tracking-wide uppercase">{{ $book->category->name }}</span>
                            <a href="{{ route('books.show', $book->id) }}">
                                <h3 class="font-serif font-bold text-lg mb-1 leading-snug truncate hover:text-emerald transition" title="{{ $book->title }}">{{ $book->title }}</h3>
                            </a>
                            <p class="text-sm text-gray-600 mb-3">{{ $book->author }}</p>
                            <p class="text-xs text-gray-500 mb-4 flex-grow">{{ Str::limit($book->description, 80) }}</p>

                            <div class="flex justify-between items-center mb-4">
                                <span class="font-bold text-lg text-navy">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">Stok: {{ $book->stock }}</span>
                            </div>

                            @if($book->stock > 0)
                                <form action="{{ route('cart.add', $book->id) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit" class="w-full bg-emerald text-white rounded py-2 text-sm font-medium hover:bg-opacity-90 transition">Add to Cart</button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 rounded py-2 text-sm font-medium cursor-not-allowed mt-auto">Out of Stock</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-lg">No books found matching your criteria.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $books->links() }}
            </div>
        </main>

        <footer class="bg-white border-t mt-auto py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-500 text-sm font-medium">&copy; {{ date('Y') }} Academia Reads. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            const mainContent = document.getElementById('main-content');

            preloader.classList.add('opacity-0');

            setTimeout(() => {
                preloader.style.display = 'none';
                mainContent.classList.remove('opacity-0');
            }, 500);
        });
    </script>
</body>
</html>
