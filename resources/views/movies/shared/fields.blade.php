@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'show';
@endphp
<div class="flex mt-6 justify-between">
    <div class="space-y-6 flex-row grow">
        <x-field.input name="title" label="Title" :readonly="$readonly" value="{{ old('title', $movie->title) }}" />
        <div class="flex space-x-4">
            <x-field.input name="year" label="Year" width="sm" :readonly="$readonly" value="{{ old('year', $movie->year) }}" />
            <div>
                <x-input-label for="genrefilter" :value="__('Genres')" />
                <x-field.select id="genrefilter" name="genre_code" label="Genre" width="md" :readonly="$readonly" value="{{ old('genre_code', $movie->genre_code) }}" :options="$genres" />
            </div>
        </div>
        <x-field.input name="trailer_url" label="Trailer" :readonly="$readonly" value="{{ old('trailer_url', $movie->trailer_url) }}" />
        <x-field.text-area name="synopsis" label="Synopsis" :readonly="$readonly" value="{{ old('synopsis', $movie->synopsis) }}" />
    </div>
    <div class="pb-6 mb-10 ml-20">
        <x-field.image name="poster_filename" label="Poster" width="md" :readonly="$readonly" deleteTitle="Delete Poster" :deleteAllow="($mode == 'edit') && ($movie->poster_filename)" deleteForm="form_to_delete_photo" :imageUrl="$movie->PosterFullUrl" />
    </div>
</div>