@extends('layouts.main')

@section('header-title', 'Genres')

@section('main')
<div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
    <form method="POST" action="{{ route('configuration.update')}}">
        <div class="mt-6 space-y-4">
            @csrf
            @method('PUT')
        </div>
        <div class="mt-6">
            <x-field.input name="price" label="Price" :readonly="false" value="{{ old('price', $config->ticket_price) }}" />
            <x-field.input name="discount" label="Customer Discount" :readonly="false" value="{{ old('discount', $config->registered_customer_ticket_discount) }}" />
            <div class="mt-10">
                <x-button element="submit" type="dark" text="Save" class="uppercase" />
            </div>
        </div>
    </form>
</div>
@endsection