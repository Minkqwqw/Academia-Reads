@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('header', 'Edit Buku')

@section('content')
<div class="bg-white rounded-lg shadow-sm border p-6 max-w-4xl mx-auto">
    <div class="mb-6 border-b pb-4 flex justify-between items-center">
        <a href="{{ route('admin.books.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">&larr; Kembali ke Daftar Buku</a>
    </div>

    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('title')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                <input type="text" name="author" value="{{ old('author', $book->author) }}" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('author')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id') ?? $book->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $book->price) }}" required min="0" class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('price')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" required min="0" class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                @error('stock')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Buku</label>
            <textarea name="description" required rows="5" class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald resize-none">{{ old('description', $book->description) }}</textarea>
            @error('description')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <div class="mb-8 flex gap-6 items-start">
            @if($book->cover_image)
                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-1">Cover Saat Ini</p>
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-24 rounded border shadow-sm">
                </div>
            @endif
            <div class="flex-grow">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Cover Baru (Opsional)</label>
                <input type="file" name="cover_image" accept="image/*" class="w-full rounded border border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:border-emerald focus:ring-emerald">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah cover.</p>
                @error('cover_image')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-emerald text-white font-medium py-2 px-8 rounded hover:bg-opacity-90 transition">Update Data Buku</button>
        </div>
    </form>
</div>
@endsection
