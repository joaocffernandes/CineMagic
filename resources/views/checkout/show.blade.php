@extends('layouts.main')

@section('header-title', 'Checkout')

@section('main')
<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-xl my-6">Finalize Your Purchase</h2>
        <form method="POST" action="{{ route('cart.confirm') }}">
            @csrf
            <!-- Summary of Cart -->
            <div class="mb-4">
                <h3 class="text-lg">Order Summary</h3>
                @foreach(session('cart', collect()) as $item)
                    <div class="ml-4">&#8226 {{ $item->screening->movie->title }} - {{ $item->price }}€</div>
                @endforeach
                <div class="mt-2">
                    <strong>Total: {{ session('cart', collect())->sum('price') }}€</strong>
                </div>
            </div>

            <!-- Payment Information -->
            <div>
                <!-- Name -->
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- NIF (optional) -->
                <div class="mt-4">
                    <x-input-label for="nif" :value="__('NIF (Optional)')" />
                    <x-text-input id="nif" name="nif" type="text" class="block mt-1 w-full" :value="old('nif', $customer->nif ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('nif')" />
                </div>

                <!-- Payment Type -->
                <div class="mt-4">
                    <x-input-label for="payment_type" :value="__('Tipo de Pagamento')" />
                    <x-field.select name="payment_type" id="payment_type" value="{{ old('payment_type', $customer->payment_type ?? 'VISA') }}" :options="['VISA' => 'VISA', 'PAYPAL' => 'PAYPAL', 'MBWAY' => 'MBWAY']" />
                    <x-input-error class="mt-2" :messages="$errors->get('payment_type')" />
                </div>
                
                <!-- Payment Reference -->
                <div class="mt-4" id="payment_ref_container">
                    <x-input-label for="payment_ref" :value="__('Payment Reference')" />
                    <x-text-input id="payment_ref" name="payment_ref" type="text" class="block mt-1 w-full" :value="old('payment_ref', $customer->payment_ref ?? '')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('payment_ref')" />
                </div>

                <!-- CVC Field, shown based on payment type -->
                <div class="mt-4" id="cvc_field" style="display: none;">
                    <x-input-label for="cvc" :value="__('CVC Code')" />
                    <x-text-input id="cvc" name="cvc" type="text" class="block mt-1 w-full" :value="old('cvc')" />
                    <x-input-error class="mt-2" :messages="$errors->get('cvc')" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-4">
                    {{ __('Process Payment') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
