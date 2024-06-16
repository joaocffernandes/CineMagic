@extends('layouts.main')

@section('header-title', 'Historical Purchases')

@section('main')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg bg-white p-4">
                    <h1 class="text-xl font-semibold text-gray-900">All Purchases</h1>
                    @foreach ($purchases as $index => $purchase)
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-4">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Purchase
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    Purchase Details
                                </p>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Purchase Id
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $purchase->id }}
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Total Price
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            ${{ number_format($purchase->total_price, 2) }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Date of Purchase
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $purchase->created_at->format('d/m/Y') }}
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:flex sm:items-center sm:justify-start sm:px-6">
                                        <div class="text-sm text-gray-900">
                                            <x-button href="{{ route('purchases.receipt.download', $purchase->id) }}" text="Download Receipt" type="primary" class="text-indigo-600 hover:text-indigo-900"/>
                                        </div>

                                        <div class="text-sm text-gray-900 ml-4">
                                            <x-button href="{{ route('purchases.show', $purchase->id) }}" text="More Details" type="secondary" class="text-indigo-600 hover:text-indigo-900"/>
                                        </div>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $purchases->links() }} {{-- Paginação dos purchases --}}
    </div>
</div>
@endsection
