@extends('layouts.main')

@section('header-title', 'List of Tickets')

@section('main')
    <!-- Só para testar o cart, não sei se é presiso uma tabela de tickets para o cliente -->
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            @if(!$purchase)
                <x-tickets.filter-card
                    :filterAction="route('tickets.index')"
                    :resetUrl="route('tickets.index')"
                    :movies="$movies?->toArray()"
                    :movie="old('movie', $filterByMovie ?? null)"
                    :date="old('date', $filterByDate ?? null)"
                    class="mb-6"
                    />
            @endif
            @can('create', App\Models\Ticket::class)
                <div class="flex items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('tickets.create') }}"
                        text="Create a new ticket"
                        type="success"/>
                </div>
            @endcan
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
            <x-tickets.table :tickets="$tickets"
                :showView="true"
                :showEdit="Auth::check() && Auth::user()->type != 'C'"
                :showDelete="Auth::check() && Auth::user()->type != 'C'"
                :showAddToCart="false"
                :showRemoveFromCart="false"
            />
            </div>
            @if($purchase)
                <x-button class="mt-10" href="{{ route('purchases.show', $purchase) }}" type="primary" text="Return to purchase"/>
            @else
                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
