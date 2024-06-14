@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'show';
@endphp

<x-field.input name="title" label="Title" :readonly="true" value="{{ old('title', $movie->title) }}" />
<div class="flex space-x-4 mt-4">
    <div>
        <x-input-label for="theater" :value="__('Theater')" />
        <x-field.select id="theater" name="theater" label="Theater" width="md" :readonly="$readonly" value="{{ old('theater', $screening->theater_id) }}" :options="$theaters" />
    </div>
</div>

<div id="screenings-container" class="flex flex-col space-y-4">
    <div class="flex space-x-4">
        <x-field.input name="date[]" label="Date" width="sm" :readonly="$readonly" value="{{ old('date.0', $screening->date) }}" />
        <x-field.input name="start_time[]" label="Hour" width="sm" :readonly="$readonly" value="{{ old('start_time.0', $screening->start_time) }}" />
    </div>
</div>
@if($mode == 'create')
<div class="flex mt-6 space-x-4">
    <x-button type="button" text="Add Screening" onclick="addScreeningField()" />
</div>
@endif

<script>
    function addScreeningField() {
        const container = document.getElementById('screenings-container');
        const index = container.children.length;
        const newFields = document.createElement('div');
        newFields.classList.add('flex', 'space-x-4', 'mt-4');
        newFields.innerHTML = `
            <div>
                <x-field.input name="date[]" label="Date" width="sm" value="" />
            </div>
            <div>
                <x-field.input name="start_time[]" label="Hour" width="sm" value="" />
            </div>
        `;
        container.appendChild(newFields);
    }
</script>