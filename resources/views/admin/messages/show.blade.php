<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Message Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:underline">&larr; Back to Inbox</a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="border-b pb-4 mb-6">
                    <h1 class="text-2xl font-bold mb-2">{{ $message->subject }}</h1>
                    <div class="flex justify-between text-sm text-gray-600">
                        <div>From: <strong>{{ $message->name }}</strong> &lt;{{ $message->email }}&gt;</div>
                        <div>{{ $message->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                
                <div class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                    {{ $message->message }}
                </div>
                
                <div class="mt-8 pt-6 border-t text-sm text-gray-500">
                    Message ID: #{{ $message->id }} | Status: {{ $message->is_read ? 'Read' : 'Unread' }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
