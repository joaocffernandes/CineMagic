@extends('layouts.main')

@section('header-title', 'Register')

@section('main')
<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-xl my-6">Register a new user</h2>
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <div class="flex justify-between">
                <div class="grow">
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="nif" :value="__('NIF')" />
                        <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full" :value="old('nif')" required autofocus autocomplete="nif" />
                        <x-input-error class="mt-2" :messages="$errors->get('nif')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="payment_type" :value="__('Tipo de Pagamento')" />
                        <x-field.select name="payment_type" id="payment_type" value="{{ old('payment_type') }}" :options="['VISA' => 'VISA', 'PAYPAL' => 'PAYPAL', 'MBWAY' => 'MBWAY']" />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_type')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="payment_ref" :value="__('Referência')" />
                        <x-text-input id="payment_ref" name="payment_ref" type="text" class="mt-1 block w-full" :value="old('payment_ref')" required autofocus autocomplete="payment_ref" />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_ref')" />
                    </div>
                </div>

                <div>
                    <div class="pb-6 mb-10 ml-20">
                        <x-field.image id="avatar" name="photo_filename" label="Photo" width="md" :readonly="false" deleteTitle="Delete Photo" :deleteAllow="false" :imageUrl="$user->PhotoFullUrl" />                    
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection