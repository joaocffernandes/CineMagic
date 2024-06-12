@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'show';
@endphp
<x-field.input name="title" label="Title" :readonly="$readonly" value="{{ old('title', $movie->title) }}" />
<div class="flex space-x-4">
    <x-field.input name="year" label="Year" width="sm" :readonly="$readonly" value="{{ old('year', $movie->year) }}" />
    <x-field.select  name="genre_code" label="Genre" width="md" :readonly="$readonly" value="{{ old('genre_code', $movie->genre_code) }}" :options="$genres" />
</div>
<x-field.input name="trailer_url" label="Trailer" :readonly="$readonly" value="{{ old('trailer_url', $movie->trailer_url) }}" />
<x-field.text-area name="synopsis" label="Synopsis" :readonly="$readonly" value="{{ old('synopsis', $movie->synopsis) }}" />