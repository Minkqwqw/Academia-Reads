@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('header', 'Kelola Kategori')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    
    <!-- Form Tambah -->
    <div class="lg:w-1/3">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="font-serif font-bold text-lg mb-4">Tambah Kategori Baru</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="name" required class="w-full rounded border-gray-300 focus:border-emerald focus:ring-emerald">
                    @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="w-full bg-emerald text-white rounded py-2 font-medium hover:bg-opacity-90 transition">Simpan Kategori</button>
            </form>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="lg:w-2/3">
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $index => $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
