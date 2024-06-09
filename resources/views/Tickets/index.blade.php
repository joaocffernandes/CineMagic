@extends('layouts.main')

@section('header-title', 'List of Tickets')

@section('main')
    <!-- Só para testar o cart, não sei se é presiso uma tabela de tickets para o cliente -->
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            {{-- Asumindo que você pode ter filtros para tickets também, por exemplo, por filme ou data --}}
            <x-tickets.filter-card
                :filterAction="route('tickets.index')"
                :resetUrl="route('tickets.index')"
                :movies="$movies->pluck('title', 'id')->toArray()" {{-- Substituindo cursos por filmes --}}
                :movie="old('movie', $filterByMovie)"
                :date="old('date', $filterByDate)"
                class="mb-6"
                />
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
                :showEdit="true"
                :showDelete="true"
                :showAddToCart="true"
                :showRemoveFromCart="true"
                />
            </div>
            <div class="mt-4">
                {{ $tickets->links() }} {{-- Paginação dos tickets --}}
            </div>
        </div>
    </div>
@endsection
