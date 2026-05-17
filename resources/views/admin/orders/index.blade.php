@extends('layouts.admin')

@section('title', 'List Pesanan Buku')
@section('header', 'Order Monitoring')

@section('content')
<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($orders as $order)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                    <div class="text-sm text-gray-500 line-clamp-2" title="{{ $order->shipping_address }}">{{ Str::limit($order->shipping_address, 40) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-navy">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center gap-2">
                        <!-- 1 & 2: Status Select and Update Button -->
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="text-xs rounded border-gray-300 focus:border-emerald focus:ring-emerald py-1.5
                                {{ $order->status == 'pending' ? 'bg-yellow-50 text-yellow-800' : '' }}
                                {{ $order->status == 'processing' ? 'bg-blue-50 text-blue-800' : '' }}
                                {{ $order->status == 'completed' ? 'bg-green-50 text-green-800' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-50 text-red-800' : '' }}
                            ">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="bg-emerald text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-opacity-90 transition">Update</button>
                        </form>

                        <!-- 3: Lihat Detail Button -->
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-opacity-90 transition">Lihat Detail</a>

                        <!-- 4: Hapus History Button -->
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus history pesanan ini? Stok akan dikembalikan jika status completed.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-opacity-90 transition">Hapus History</button>
                        </form>
                    </div>
                </td>            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 border-t">
        {{ $orders->links() }}
    </div>
</div>
@endsection
