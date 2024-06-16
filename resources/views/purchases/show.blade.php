@extends('layouts.main')

@section('header-title', 'Purchase Details')

@section('main')
<div class="flex justify-center">
    <div class="max-w-4xl w-full my-6 px-6 py-4 bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-lg text-gray-900 dark:text-gray-50">
        <h2 class="text-2xl font-semibold mb-6">Purchase Details</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <h3 class="text-lg font-medium underline mb-2">General Information</h3>
                <p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
                <p><strong>Total Price:</strong> €{{ number_format($purchase->total_price, 2) }}</p>
                <p><strong>Purchase Date:</strong> {{ $purchase->created_at->format('d/m/Y') }}</p>
            </div>
            
            <div>
                <h3 class="text-lg font-medium underline mb-2">Customer Information</h3>
                <p><strong>Name:</strong> {{ $purchase->customer_name }}</p>
                <p><strong>Email:</strong> {{ $purchase->customer_email }}</p>
                @if ($purchase->customer_nif)
                    <p><strong>NIF:</strong> {{ $purchase->customer_nif }}</p>
                @endif
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium underline mb-2">Payment Details</h3>
            <p><strong>Payment Type:</strong> {{ $purchase->payment_type }}</p>
            <p><strong>Payment Reference:</strong> {{ $purchase->payment_ref }}</le>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium underline mb-2">Tickets Purchased</h3>
            @if ($purchase->tickets && $purchase->tickets->isNotEmpty())
                <ul class="list-disc list-inside">
                    @foreach ($purchase->tickets as $ticket)
                        <li>
                            Movie: <strong>{{ $ticket->screening->movie->title }}</strong> - Seat: <strong>{{ $ticket->seat->row . $ticket->seat->seat_number }}</strong> on Date: <strong>{{ $ticket->screening->date }}</strong>
                            - <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-500 hover:text-blue-700">View Details</a>
                        </li>
                    @endforeach
                </ul>
                <!-- Botão para ver todos os tickets desta compra -->
                <div class="flex justify-between">
                    <div class="mt-4">
                        <x-button href="{{ route('tickets.index', ['purchase' => $purchase->id]) }}" text="View Tickets" type="dark" />
                    </div>
                    <div class="mt-4">
                        <x-button href="{{ route('purchases.receipt.download', $purchase->id) }}" text="Download Receipt" type="primary" class="text-indigo-600 hover:text-indigo-900"/>
                    </div>

                    @if(Auth::user()?->type == 'E' || Auth::user()?->type == 'A')
                        <div class="mt-4">
                            <x-button href="{{ route('purchases.resend_email', $purchase->id) }}" text="Resend email" type="primary" class="text-indigo-600 hover:text-indigo-900"/>
                        </div>
                    @endif
                </div>
            @else
                <p>No tickets were found for this purchase.</p>
            @endif
        </div>
    </div>
</div>
@endsection
