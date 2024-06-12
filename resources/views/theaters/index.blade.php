@extends('layouts.main')

@section('header-title', 'Theaters')

@section('main')
<div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
        <div class="flex items-center gap-4 mb-4">
            <x-button href="{{ route('theaters.create') }}" text="Insert a new Theater" type="success" />
        </div>
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50 flex grow">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300 flex grow">
                <x-theather.table class="flex-grow" :theaters="$theaters"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true" 
                    />
            </div>
        </div>
    </div>
</div>
@endsection