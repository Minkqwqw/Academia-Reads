@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Overview')

@section('content')
@php
    $totalBooks = \App\Models\Book::count();
    $totalCategories = \App\Models\Category::count();
    $totalUsers = \App\Models\User::where('role', 'user')->count();
    $totalOrders = \App\Models\Order::count();
    $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('total_price');
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow-sm border p-6 flex items-center">
        <div class="bg-blue-100 text-blue-600 p-4 rounded-full mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Buku</p>
            <p class="text-2xl font-bold text-navy">{{ $totalBooks }}</p>
        </div>
    </div>
    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow-sm border p-6 flex items-center">
        <div class="bg-green-100 text-green-600 p-4 rounded-full mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Kategori</p>
            <p class="text-2xl font-bold text-navy">{{ $totalCategories }}</p>
        </div>
    </div>
    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow-sm border p-6 flex items-center">
        <div class="bg-purple-100 text-purple-600 p-4 rounded-full mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">User Terdaftar</p>
            <p class="text-2xl font-bold text-navy">{{ $totalUsers }}</p>
        </div>
    </div>
    <!-- Card 4 -->
    <div class="bg-white rounded-lg shadow-sm border p-6 flex items-center">
        <div class="bg-yellow-100 text-yellow-600 p-4 rounded-full mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Pesanan</p>
            <p class="text-2xl font-bold text-navy">{{ $totalOrders }}</p>
        </div>
    </div>
    <!-- Card 5 -->
    <div class="bg-white rounded-lg shadow-sm border p-6 flex items-center">
        <div class="bg-red-100 text-red-600 p-4 rounded-full mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Pendapatan</p>
            <p class="text-2xl font-bold text-navy">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection
