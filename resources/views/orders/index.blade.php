<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(count($orders) > 0)
                    @foreach($orders as $order)
                        <div class="border rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-center border-b pb-4 mb-4">
                                <div>
                                    <span class="font-bold text-gray-700">Order #{{ $order->id }}</span>
                                    <span class="ml-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between items-center py-2 text-sm">
                                        <div class="flex items-center gap-4">
                                            @if($item->book && $item->book->cover_image)
                                                <img src="{{ asset('storage/' . $item->book->cover_image) }}" class="w-10 h-14 object-cover">
                                            @endif
                                            <div>
                                                <div class="font-bold">{{ $item->book ? $item->book->title : 'Deleted Book' }}</div>
                                                <div class="text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        <div class="font-bold">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center border-t pt-4">
                                <div class="text-sm text-gray-600 flex items-center gap-4">
                                    <span>Payment: {{ $order->payment_method }}</span>
                                    @if($order->status === 'pending')
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition">Cancel Order</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="font-bold text-lg">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        You have not placed any orders yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
