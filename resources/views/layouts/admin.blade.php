<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - Academia Reads</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-navy text-white flex-shrink-0 min-h-screen flex flex-col">
        <div class="p-6 border-b border-gray-700">
            <h1 class="font-serif text-2xl font-bold">Academia Reads</h1>
            <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
        </div>
        <nav class="flex-grow py-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-emerald' : '' }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="block px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 border-l-4 border-emerald' : '' }}">Kelola Kategori</a>
                </li>
                <li>
                    <a href="{{ route('admin.books.index') }}" class="block px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.books.*') ? 'bg-gray-800 border-l-4 border-emerald' : '' }}">Kelola Buku</a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="block px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 border-l-4 border-emerald' : '' }}">List User</a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="block px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 border-l-4 border-emerald' : '' }}">List Pesanan</a>
                </li>
                <li>
                    <a href="{{ route('admin.messages.index') }}" class="block px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.messages.*') ? 'bg-gray-800 border-l-4 border-emerald' : '' }}">Pesan Masuk</a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('home') }}" class="block w-full text-center py-2 text-sm text-gray-300 hover:text-white transition">Lihat Website</a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col">
        <!-- Top Navbar -->
        <header class="bg-navy text-white shadow-sm flex items-center justify-between px-8 py-4">
            <h2 class="font-serif text-xl font-bold">@yield('header', 'Dashboard')</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white text-xs px-3 py-1 rounded transition">Logout</button>
                </form>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-8 flex-grow">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
