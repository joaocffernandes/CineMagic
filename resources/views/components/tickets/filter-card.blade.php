<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <div>
                    <!-- Dropdown for selecting a movie -->
                    <x-field.select name="movie" label="Movie"
                        value="{{ $movie }}"
                        :options="$listMovies"/>
                </div>
                <div>
                    <!-- Input for selecting a date -->
                    <x-field.input type="date" name="date" label="Date" class="grow"
                        value="{{ $date }}"/>
                </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <!-- Submit button for the form -->
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <!-- Reset button to clear the form and reset filters -->
                    <x-button element="a" type="light" text="Reset" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>
