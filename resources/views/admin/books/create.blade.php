@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')
@section('header', 'Tambah Buku Baru')

@section('content')
<div class="bg-white rounded-lg shadow-sm border p-6 max-w-4xl mx-auto">
    <div class="mb-6 border-b pb-4">
        <a href="{{ route('admin.books.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">&larr; Kembali ke Daftar Buku</a>
    </div>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('title')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                <input type="text" name="author" value="{{ old('author') }}" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('author')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" required min="0" class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('price')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock') }}" required min="0" class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('stock')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Buku</label>
            <textarea name="description" required rows="5" class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald resize-none">{{ old('description') }}</textarea>
            @error('description')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image (Wajib)</label>
            <input type="file" name="cover_image" accept="image/*" required class="w-full rounded border border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:border-emerald focus:ring-emerald">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal: 2MB.</p>
            @error('cover_image')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-emerald text-white font-medium py-2 px-8 rounded hover:bg-opacity-90 transition">Simpan Buku Baru</button>
        </div>
    </form>
</div>
@endsection
