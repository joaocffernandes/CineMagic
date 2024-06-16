{{-- resources/views/tickets/ticket_html.blade.php --}}
@extends('layouts.main')

@section('header-title', 'Ticket Details')

@section('main')
<div class="max-w-4xl mx-auto p-5 bg-white shadow-xl rounded-lg my-6">
    <h2 class="text-3xl font-bold mb-8 text-center text-blue-800">Ticket for "{{ $ticket->screening->movie->title }}"</h2>
    <div class="flex justify-center">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-gray-700">
            <!-- Movie Details -->
            <div class="p-4">
                <h3 class="text-xl font-semibold mb-4">Movie Details</h3>
                <p><strong>Title:</strong> {{ $ticket->screening->movie->title }}</p>
                <p><strong>Date:</strong> {{ $ticket->screening->date }}</p>
                <p><strong>Time:</strong> {{ $ticket->screening->start_time }}</p>
                <p><strong>Theater:</strong> {{ $ticket->screening->theater->name }}</p>
            </div>
            <!-- Ticket & Purchase Info -->
            <div class="p-4">
                <h3 class="text-xl font-semibold mb-4">Ticket & Purchase Info</h3>
                <p><strong>Ticket ID:</strong> {!! $ticket->id ?? '<i>N/A</i>' !!}</p>
                <p><strong>Seat:</strong> {{ $ticket->seat->row . $ticket->seat->seat_number }}</p>
                <p><strong>Price:</strong> €{{ number_format($ticket->price, 2) }}</p>
                <p><strong>Status:</strong> {{ $ticket->status === 'valid' ? 'Valid' : 'Invalid' }}</p>
            </div>
            <!-- Customer Info -->
            <div class="p-4">
                <h3 class="text-xl font-semibold mb-4">Customer Info</h3>
                @if ($ticket->purchase && $ticket->purchase->customer)
                    <p><strong>Name:</strong> {{ $ticket->purchase->customer_name ?? 'N/A'}}</p>
                    <p><strong>Email:</strong> {{ $ticket->purchase->customer_email ?? 'N/A' }}</p>
                    @if ($ticket->purchase->customer?->user?->photo_filename)
                        <div class="mt-2">
                            <x-field.image name="photo_filename" label="Photo" width="md" :readonly="$readonly" :imageUrl="$ticket->purchase->customer->user->photo_filename ?? 'N/A'" />
                        </div>
                    @endif
                @else
                    <p>No registered customer details available.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-10 text-center">
        @if($ticket->id)
            <a href="{{ route('tickets.receipt.download', $ticket->id) }}"
                class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                Download Ticket
            </a>
        @endif
    </div>
</div>
@endsection
