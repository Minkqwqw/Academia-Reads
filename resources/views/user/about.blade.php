<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us - Academia Reads</title>
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
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-emerald transition">Cart</a>
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

                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
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
        <div class="bg-white rounded-xl shadow-sm border p-10 md:p-16 text-center">

            <h1 class="text-4xl md:text-5xl font-serif font-bold text-navy mb-8">About Academia Reads</h1>

            <div class="space-y-6 text-gray-700 text-lg leading-relaxed text-left">
                <p>
                    <strong>Academia Reads</strong> adalah toko buku digital terkemuka yang berdedikasi untuk menyediakan akses ke literatur akademis, jurnal penelitian, dan buku-buku edukatif berkualitas tinggi. Kami hadir untuk melayani kebutuhan intelektual mahasiswa, dosen, peneliti, dan para pembelajar sepanjang hayat.
                </p>
                <p>
                    <strong>Visi Kami:</strong> Menjadi platform penyedia literatur akademik nomor satu di Indonesia yang mendemokratisasi akses terhadap ilmu pengetahuan dan mendukung terciptanya masyarakat yang literat dan kritis.
                </p>
                <p>
                    <strong>Misi Kami:</strong>
                    <ul class="list-disc list-inside ml-6 space-y-2 mt-2">
                        <li>Menjembatani pencari ilmu dengan sumber referensi yang otentik dan terpercaya.</li>
                        <li>Menyediakan platform e-commerce yang intuitif, aman, dan mudah digunakan untuk pengalaman berbelanja buku yang optimal.</li>
                        <li>Mendukung perkembangan ekosistem pendidikan melalui kurasi buku-buku akademik yang relevan dengan perkembangan zaman.</li>
                    </ul>
                </p>
                <p>
                    Dengan platform kami, Anda dapat dengan mudah mencari, mengeksplorasi, dan memesan buku favorit Anda secara langsung. Selamat membaca dan menemukan harta karun akademik Anda selanjutnya!
                </p>
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
