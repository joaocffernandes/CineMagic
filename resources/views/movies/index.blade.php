@extends('layouts.main')
@section('header-title', 'List of Movies')
@section('main')
<div>
    <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
        <div class="flex items-center gap-4 mb-4">
            <x-button href="{{ route('movies.create') }}" text="Insert a new Movie" type="success" />
        </div>
        <div class="flex flex-wrap justify-around gap-2font-base text-sm text-gray-700 dark:text-gray-300">
            @foreach ($movies as $movie)
            <div class="relative m-10 flex flex-col h-full overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md w-[30%] min-w-[250px] m-4 h-[650px]">
                <a class="flex items-center justify-center" href="#">
                    <img class="object-cover w-45 h-80" src="{{ $movie->poster_filename ? asset('storage/posters/' . $movie->poster_filename) : asset('storage/posters/_no_poster_1.png') }}" alt='Poster' />
                </a>
                <div class="mt-4 px-5 pb-5">
                    <a href="#">
                        <p class="text-lg tracking-tight text-slate-900">{{ $movie->title }}</p>
                        <p class="text-sm tracking-tight text-slate-500">{{ $movie->year }}</p>
                    </a>
                    <div class="mt-2 mb-5 h-28 overflow-y-auto px-2 py-1 leading-normal">
                        <p>{{ $movie->synopsis }}</p>
                    </div>
                </div>
                <div class="px-5 pb-5 mt-auto">
                    <x-button class="w-full" href="#" text="Buy Tickets" type="dark" /> <!-- Adjusted to w-full -->
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection