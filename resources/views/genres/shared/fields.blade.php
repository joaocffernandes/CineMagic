@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'edit';
@endphp
<x-field.input name="code" label="Code" :readonly="$readonly" value="{{ old('code', $genre->code) }}" />
<x-field.input name="name" label="Name" :readonly="false" value="{{ old('name', $genre->name) }}" />