@extends('layouts.main')
@section('header-title', 'Create Screening')
@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Movie "{{ $movie->title }}"
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 mb-6">
                        Click on "Save" button to store the information.
                    </p>
                </header>
                <form method="POST" action="{{ route('screenings.store', ['movie'=>$movie]) }}">
                    <div class="mt-6 space-y-4">
                        @csrf
                        @method('POST')
                        @include('screenings.shared.fields', ['mode' => 'create'])
                    </div>
                    <div class="flex mt-6">
                        <x-button element="submit" type="dark" text="Save" class="uppercase" />
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection

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