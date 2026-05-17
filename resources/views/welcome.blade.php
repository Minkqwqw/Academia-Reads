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

                <!-- Search & Filter Form -->
                <form action="{{ route('books.search') }}" method="GET" class="max-w-4xl mx-auto bg-white rounded-xl shadow-xl text-gray-900 border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-2xl">
                    <!-- Primary Search Row -->
                    <div class="flex items-center p-2 relative z-10 bg-white">
                        <svg class="w-6 h-6 text-gray-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="w-full border-0 focus:outline-none focus:ring-0 bg-transparent px-4 py-3 text-lg" id="mainSearchInput" autocomplete="off">

                        <button type="button" id="toggleFiltersBtn" class="px-4 py-2 text-sm text-gray-500 hover:text-emerald font-medium flex items-center gap-1 transition">
                            <svg class="w-4 h-4 transition-transform duration-300" id="filterIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            Filters
                        </button>

                        <button type="submit" class="bg-emerald text-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-90 transition ml-2">Search</button>
                    </div>

                    <!-- Expandable Filters -->
                    <div id="advancedFilters" style="max-height: 0px;" class="transition-all duration-500 ease-in-out overflow-hidden bg-gray-50 border-t border-gray-100">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row gap-4 mb-4">
                                <div class="w-full md:w-1/2 text-left">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                                    <select name="category" class="w-full rounded-md border-gray-300 px-4 py-2 focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-1/2 text-left">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Author</label>
                                    <input type="text" name="author" value="{{ request('author') }}" placeholder="Author name..." class="w-full rounded-md border-gray-300 px-4 py-2 focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50">
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-4 items-end">
                                <div class="w-full md:w-2/3 text-left">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Price Range (Rp)</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full rounded-md border-gray-300 px-3 py-2 focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50">
                                        <span class="text-gray-400">-</span>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full rounded-md border-gray-300 px-3 py-2 focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 flex justify-end items-center h-full pb-2">
                                     <button type="button" id="clearFiltersBtn" class="text-gray-500 hover:text-red-500 text-sm font-bold transition">Clear Filters</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>            </div>
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
            // Preloader Logic
            const preloader = document.getElementById('preloader');
            const mainContent = document.getElementById('main-content');

            preloader.classList.add('opacity-0');

            setTimeout(() => {
                preloader.style.display = 'none';
                mainContent.classList.remove('opacity-0');
            }, 500);

            // Expandable Filter Logic
            const toggleBtn = document.getElementById('toggleFiltersBtn');
            const advancedFilters = document.getElementById('advancedFilters');
            const filterIcon = document.getElementById('filterIcon');

            // Check if URL has filter params to keep it open initially
            const urlParams = new URLSearchParams(window.location.search);
            const hasFilters = urlParams.get('category') || urlParams.get('author') || urlParams.get('min_price') || urlParams.get('max_price');

            if (hasFilters) {
                // If filters are active, keep the section expanded
                advancedFilters.style.maxHeight = advancedFilters.scrollHeight + "px";
                filterIcon.classList.add('rotate-180');
            }

            if (toggleBtn && advancedFilters) {
                toggleBtn.addEventListener('click', function() {
                    // Check if it's currently expanded
                    if (advancedFilters.style.maxHeight && advancedFilters.style.maxHeight !== "0px") {
                        // Collapse
                        advancedFilters.style.maxHeight = "0px";
                        filterIcon.classList.remove('rotate-180');
                    } else {
                        // Expand
                        advancedFilters.style.maxHeight = advancedFilters.scrollHeight + "px";
                        filterIcon.classList.add('rotate-180');
                    }
                });
            }

            // Clear Filter Buttons
            const clearBtn = document.getElementById('clearFiltersBtn');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    document.querySelectorAll('#advancedFilters input, #advancedFilters select').forEach(el => el.value = '');
                });
            }
        });

        /**
         * URL Sanitization Logic
         * Menghapus parameter pencarian yang kosong agar URL tetap bersih.
         * Contoh: /search?category=romance daripada /search?search=&category=romance&author=
         */
        const searchForm = document.querySelector('form[action="{{ route('books.search') }}"]');
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                const inputs = this.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (!input.value) {
                        input.disabled = true; // Disable input kosong agar tidak dikirim ke URL
                    }
                });
            });
        }

    </script>
</body>
</html>
