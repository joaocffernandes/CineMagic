@php
use Illuminate\Support\Arr;

// Prepend the "All Genres" option to the genres array
$modifiedGenres = Arr::prepend($genres, 'All Genres', '');
@endphp

<div>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <div>
                    <x-field.select name="genre" label="Genre"
                        value="{{ $genre }}"
                        :options="$modifiedGenres"/>
                </div>
                <div>
                    <x-field.input name="name" label="Name" class="grow"
                        value="{{ $name }}"/>
                </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Clear" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>
