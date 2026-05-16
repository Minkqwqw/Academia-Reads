@extends('layouts.admin')

@section('title', 'Pesan Masuk')
@section('header', 'Pesan dari Pengunjung')

@section('content')
<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($messages as $message)
            <tr class="{{ $message->is_read ? 'bg-gray-50' : 'bg-white' }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $message->created_at->format('d M Y, H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="font-medium text-gray-900">{{ $message->name }}</div>
                    <div class="text-gray-500">{{ $message->email }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                    <p class="font-bold text-navy">{{ $message->subject }}</p>
                    <p class="line-clamp-2 text-gray-500 mt-1" title="{{ $message->message }}">{{ $message->message }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    @if(!$message->is_read)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unread</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Read</span>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('admin.messages.show', $message->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">Lihat Detail</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 border-t">
        {{ $messages->links() }}
    </div>
</div>
@endsection
