@extends('layouts.main')
@section('header-title', 'On Screen')
@section('main')
<div>
    <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
        @if (Auth::check() && Auth::user()->type == 'A')
        <x-button href="{{ route('configuration.edit') }}" text="Change ticket price" type="success" />
        @endif
        <div class="mt-4">
            <x-input-label for="theaterfilter" :value="__('Theaters')" />
            <x-screenings.filter-card id='theaterfilter' :filterAction="route('screenings.index')" :resetUrl="route('screenings.index')" :theaters="$theaters" :theater="old('theater', $filterByTheater)" :name="old('name', $filterByName)" class="mb-6" />
        </div>
        <div class="flex flex-wrap justify-around gap-2font-base text-sm text-gray-700 dark:text-gray-300">
            @foreach ($movies as $movie)
            <div class="relative m-10 flex flex-col h-full overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md w-[30%] min-w-[250px] m-4 h-[650px] movie-container">
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
                    <select id="screeningSelect-{{ $movie->id }}" class=" mb-3 p-2 w-full rounded-md border border-gray-300">
                        @php
                        $twoWeeksLater = \Carbon\Carbon::today()->addWeeks(2);
                        @endphp

                        @foreach ($movie->screenings as $screening)
                        @php
                        $screeningDate = \Carbon\Carbon::parse($screening->date);
                        $screeningTime = \Carbon\Carbon::parse($screening->start_time)->format('H:i');
                        @endphp

                        @if ($screeningDate->isToday() || ($screeningDate > \Carbon\Carbon::today() && $screeningDate <= $twoWeeksLater)) @if (is_null($filterByTheater) || $screening->theater_id == $filterByTheater)
                            <option value="{{ $screening->id }}">
                                {{ $screeningDate->toDateString() }} at {{ $screeningTime }} on {{$theaters[$screening->theater_id]}}
                            </option>
                            @endif
                            @endif
                            @endforeach
                    </select>
                </div>
                <div class="px-5 pb-5">
                    @if (Auth::check())
                    <p class="text-base tracking-tight text-slate-900">Price: {{ $config->cprice }}€</p>
                    @endif
                    @if(Auth::guest())
                    <p class="text-base tracking-tight text-slate-900">Price: {{ $config->ticket_price }}€</p>
                    @endif
                </div>
                <!-- <div class="px-5 pb-5 mt-auto flex justify-between">
                    <x-button class="w-full" href="#" text="Buy Tickets" type="dark" /> Adjusted to w-full -->
                <div class="flex mb-5 gap-3 w-full">
                    @if (Auth::check() && Auth::user()->type == 'C' || Auth::guest())
                    <div class="justify-start ml-3">
                        <x-button href="javascript:void(0)" element="submit" type="dark" text="Buy tickets" class="uppercase" />
                    </div>
                    @endif
                    @if (Auth::check() && Auth::user()->type != 'C')
                    <div class="flex grow justify-end mt-1">
                        <x-table.icon-show class="ps-3 px-0.5" href="{{ route('movies.show', ['movie' => $movie]) }}" />
                        <x-table.icon-edit href="javascript:void(0)" class="px-0.5" onclick="editScreening({{ $movie->id }})" />
                        <x-table.icon-delete action="javascript:void(0)" class="px-0.5" onclick="deleteScreening({{ $movie->id }})" />
                    </div>
                    @endif
                </div>
                <!--</div>-->
            </div>
            @endforeach
        </div>
    </div>
</div>

<form id="deleteForm" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function editScreening(movieId) {
        const selectElement = document.getElementById(`screeningSelect-${movieId}`);
        const screeningId = selectElement.value;
        if (screeningId) {
            window.location.href = `/screenings/${screeningId}`;
        } else {
            alert('Please select a screening.');
        }
    }

    function deleteScreening(movieId) {
        const selectElement = document.getElementById(`screeningSelect-${movieId}`);
        const screeningId = selectElement.value;
        if (screeningId) {
            if (confirm('Are you sure you want to delete this screening?')) {
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/screenings/${screeningId}`;
                deleteForm.submit();
            }
        } else {
            alert('Please select a screening.');
        }
    }

    function buyTicket(movieId){
        const selectElement = document.getElementById(`screeningSelect-${movieId}`);
        const screeningId = selectElement.value;
        if (screeningId) {
            window.location.href = `/screenings/${screeningId}`;
        } else {
            alert('Please select a screening.');
        }
    }
</script>
@endsection