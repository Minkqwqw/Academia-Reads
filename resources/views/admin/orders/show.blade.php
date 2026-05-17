<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Detail #') }}{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            <div class="md:w-2/3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg border-b pb-2 mb-4">Order Items</h3>
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b last:border-0">
                            <div class="flex items-center gap-4">
                                @if($item->book && $item->book->cover_image)
                                    <img src="{{ asset('storage/' . $item->book->cover_image) }}" class="w-12 h-16 object-cover rounded">
                                @endif
                                <div>
                                    <div class="font-bold">{{ $item->book ? $item->book->title : 'Deleted Book' }}</div>
                                    <div class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</div>
                                </div>
                            </div>
                            <div class="font-bold">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                        <span class="font-bold text-lg">Total Amount:</span>
                        <span class="font-bold text-xl text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="md:w-1/3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg border-b pb-2 mb-4">Customer Details</h3>
                    <div class="mb-2"><strong>Name:</strong> {{ $order->user->name }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $order->user->email }}</div>
                    <div class="mb-4"><strong>Shipping Address:</strong> <br> {{ $order->shipping_address }}</div>
                    <div class="mb-2"><strong>Payment Method:</strong> {{ $order->payment_method }}</div>
                    <div class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg border-b pb-2 mb-4">Update Status</h3>
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 px-3 py-2 rounded mb-4 text-sm">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <select name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
