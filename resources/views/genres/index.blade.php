@extends('layouts.main')

@section('header-title', 'Genres')

@section('main')
<div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
    <div class="flex items-center gap-4 mb-4">
        <x-button href="{{ route('genres.create') }}" text="Insert a new Genre" type="success" />
    </div>
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50 flex grow">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300 flex grow">
                <x-genres.table class="flex-grow" 
                    :genres="$genres" 
                    :showEdit="true" 
                    :showDelete="true" />
            </div>
        </div>
    </div>
</div>
@endsection