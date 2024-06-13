@extends('layouts.main')
@section('header-title', 'On Screen')
@section('main')
<div>
    <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
        <div class="flex items-center gap-4 mb-4">
            <x-button href="{{ route('movies.create') }}" text="Add Movie on Screen" type="success" />
        </div>
        <div class="mt-4">
            <x-input-label for="genrefilter" :value="__('Genres')" />
            <x-movies.filter-card id='genrefilter' :filterAction="route('movies.index')" :resetUrl="route('movies.index')" :genres="$genres" :genre="old('genre', $filterByGenre)" :name="old('name', $filterByName)" class="mb-6" />
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
                        <div class="flex justify-between text-sm tracking-tight text-slate-500">
                            <p>{{ $movie->year }}</p>
                            <a href="{{ $movie->trailer_url }}" target="_blank">Trailer</a>
                        </div>
                    </a>
                    <div class="mt-2 mb-5 h-28 overflow-y-auto px-2 py-1 leading-normal">
                        <p>{{ $movie->synopsis }}</p>
                    </div>
                </div>
                <!-- <div class="px-5 pb-5 mt-auto flex justify-between">
                    <x-button class="w-full" href="#" text="Buy Tickets" type="dark" /> Adjusted to w-full -->
                <div class="flex mb-5 gap-3 w-full">
                    @if (Auth::user()->type == 'C')
                    <div class="justify-start ml-3">
                        <x-button href="#" element="submit" type="dark" text="Buy tickets" class="uppercase" />
                    </div>
                    @endif
                    @if (Auth::user()->type != 'C')
                    <div class="flex grow justify-end mt-1">
                        <x-table.icon-show class="ps-3 px-0.5" href="{{ route('movies.show', ['movie' => $movie]) }}" />
                        <x-table.icon-edit class="px-0.5" href="{{ route('movies.edit', ['movie' => $movie]) }}" />
                        <x-table.icon-delete class="px-0.5" action="{{ route('movies.destroy', ['movie' => $movie]) }}" />
                    </div>
                    @endif
                </div>
                <!--</div>-->
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection