@extends('layouts.admin')

@section('title', 'Kelola Buku')
@section('header', 'Kelola Buku')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.books.create') }}" class="bg-emerald hover:bg-opacity-90 text-white font-medium py-2 px-4 rounded transition inline-block">
        + Tambah Buku Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info Buku</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Stok</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($books as $book)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="h-16 w-12 object-cover rounded">
                    @else
                        <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Img</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $book->title }}</div>
                    <div class="text-sm text-gray-500">{{ $book->author }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $book->category->name }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                    <div class="font-medium text-gray-900">Rp {{ number_format($book->price, 0, ',', '.') }}</div>
                    <div>Stok: {{ $book->stock }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.books.edit', $book->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus buku ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 border-t">
        {{ $books->links() }}
    </div>
</div>
@endsection
