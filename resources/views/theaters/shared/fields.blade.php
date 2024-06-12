@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'show';
@endphp
<x-field.input name="name" label="Name" :readonly="$readonly" value="{{ old('name', $theater->name) }}" />
<div id="rows-container" class="mt-4">
    @if(isset($rows) != null)
    @foreach($rows as $index => $row)
    <div class="flex space-x-4" data-index="{{$index}}">
        <x-field.input name="rows[{{$index}}][row]" label="Row" width="sm" :readonly="$readonly" value="{{ old('row', $row->row) }}" />
        <x-field.input name="rows[{{$index}}][seat]" label="Number of Seats" width="sm" :readonly="$readonly" value="{{ old('seat', $row->seat_count) }}" />
    </div>
    @endforeach
    @endif
</div>
<div id="rows-container" class="mt-4">
    <!-- Existing rows will be here, if any -->
</div>

<div class="flex mt-6">
    <x-button type="success" id="add-row" text="Add Row" />
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rowIdx = document.querySelectorAll('#rows-container > div').length; // Start index for new rows, adjust accordingly if you have existing rows
        var container = document.getElementById('rows-container');
        var addButton = document.getElementById('add-row');

        addButton.addEventListener('click', function() {
            // Increment the row index each time a new row is added
            var newRowIdx = rowIdx++;

            // Create the div that will hold the inputs
            var newDiv = document.createElement('div');
            newDiv.className = 'flex space-x-4 mt-4';
            newDiv.innerHTML = `
                <input type="text" name="rows[${newRowIdx}][row]" placeholder="Row" class="input-field"/>
                <input type="text" name="rows[${newRowIdx}][seat]" placeholder="Number of Seats" class="input-field"/>
        `;

            // Append the new div to the container
            container.appendChild(newDiv);
        });
    });
</script>