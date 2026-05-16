<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart - Academia Reads</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        <h1 class="text-3xl font-serif font-bold text-navy mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3">
                    <div class="bg-white shadow-sm border rounded-lg p-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b text-gray-500 text-sm font-medium">
                                    <th class="pb-3">Product</th>
                                    <th class="pb-3 text-right">Price</th>
                                    <th class="pb-3 text-center w-32">Qty</th>
                                    <th class="pb-3 text-right">Subtotal</th>
                                    <th class="pb-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr class="border-b last:border-b-0">
                                        <td class="py-4 flex items-center gap-4">
                                            @if($details['cover_image'])
                                                <img src="{{ asset('storage/' . $details['cover_image']) }}" class="w-16 h-24 object-cover rounded shadow-sm">
                                            @else
                                                <div class="w-16 h-24 bg-gray-200 rounded shadow-sm"></div>
                                            @endif
                                            <div>
                                                <h3 class="font-serif font-bold text-navy text-lg">{{ $details['title'] }}</h3>
                                            </div>
                                        </td>
                                        <td class="py-4 text-right text-gray-700 font-medium">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td class="py-4 text-center">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center gap-2">
                                                @csrf
                                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 text-center border-gray-300 rounded shadow-sm focus:border-emerald focus:ring focus:ring-emerald focus:ring-opacity-50 text-sm py-1">
                                                <button type="submit" class="text-blue-500 hover:text-blue-700 p-1" title="Update Quantity">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="py-4 text-right font-bold text-navy">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td class="py-4 text-right">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white shadow-sm border rounded-lg p-6">
                        <h3 class="text-xl font-serif font-bold text-navy mb-4 border-b pb-2">Order Summary</h3>

                        <div class="flex justify-between text-gray-600 mb-2">
                            <span>Subtotal</span>
                            <span class="font-medium text-navy">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600 mb-6 border-b pb-4">
                            <span>Shipping</span>
                            <span class="font-medium text-navy">Free</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-navy mb-8">
                            <span>Total Harga</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Pengiriman</label>
                                <textarea name="shipping_address" required class="w-full rounded border-gray-300 shadow-sm focus:border-emerald focus:ring-emerald h-24 resize-none" placeholder="Masukkan alamat lengkap pengiriman..."></textarea>
                                @error('shipping_address')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            @auth
                                <button type="submit" class="w-full bg-emerald text-white px-4 py-3 rounded font-medium hover:bg-opacity-90 transition shadow-sm">Konfirmasi & Bayar di Tempat (COD)</button>
                            @else
                                <a href="{{ route('login') }}" class="w-full block text-center bg-navy text-white px-4 py-3 rounded font-medium hover:bg-opacity-90 transition shadow-sm">Login untuk Checkout</a>
                            @endauth
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow-sm border rounded-lg p-16 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-500 text-lg mb-6">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('home') }}" class="inline-block bg-emerald text-white px-6 py-2 rounded font-medium hover:bg-opacity-90 transition">Lanjut Belanja</a>
            </div>
        @endif
    </main>

    <footer class="bg-white border-t mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500 text-sm font-medium">&copy; {{ date('Y') }} Academia Reads. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
