@extends('layouts.main')

@section('header-title', 'Choice of the Seat')

@section('main')
<style>
    .seat input[type="checkbox"]:checked + label {
        outline: 2px solid green;
    }
    .seat input[type="checkbox"][disabled] + label img {
        filter: grayscale(100%); 
        cursor: not-allowed; 
    }
    .seat label {
        cursor: pointer;
    }
    .seat input[type="checkbox"][disabled] + label {
        cursor: not-allowed;
    }
</style>
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-xl font-semibold mb-4">Select Your {{ $quantTickets }} Seats</h1>
    <form action="{{ route('cart.createTicketAndAddToCart') }}" method="POST" id="form_seat">
        @csrf
        <x-button text="Reserve Seats" type="primary" :element="__('submit')" class="mt-10 mb-10" />
        <input type="hidden" name="screening" value="{{ $screening->id }}">
        @php $currentRow = null; @endphp
        @foreach ($screening->theater->seat->sortByDesc('row') as $seat)
            @if ($seat->row !== $currentRow)
                @if ($currentRow !== null)
                    </div> <!-- Close the flex container for the previous row -->
                @endif
                <h2 class="text-lg font-medium my-2">Row {{ $seat->row }}</h2>
                <div class="flex space-x-2 mb-4"> <!-- Open a new flex container for the row -->
                @php $currentRow = $seat->row; @endphp
            @endif
            <div class="seat relative">
                <input type="checkbox" name="seats[]" value="{{ $seat->id }}" id="seat-{{ $seat->id }}" class="opacity-0 absolute inset-0 w-full h-full z-10 cursor-pointer" {{ in_array($seat->id, $seatsDisabledList) ? 'disabled' : '' }}>
                <label for="seat-{{ $seat->id }}" class="block cursor-pointer">
                    <img src="{{ asset('images/seat.png') }}" alt="Seat Image" class="mx-auto">
                    <span class="block text-center text-xs">{{ $seat->seat_number }}</span>
                </label>
            </div>
        @endforeach
        </div> <!-- Close the last flex container -->
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#form_seat');
        const seats = document.querySelectorAll('.seat input[type="checkbox"]:not([disabled])');

        form.addEventListener('submit', function(event) {
            const checkedCount = Array.from(seats).filter(chk => chk.checked).length;
            const requiredSeats = {{ $quantTickets }};
            if (checkedCount != requiredSeats) {
                alert('Please select exactly ' + requiredSeats + ' seats.');
                event.preventDefault();  // Prevent the form from being submitted
                return false;
            }
            return true;
        });

        seats.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const checkedCount = Array.from(seats).filter(chk => chk.checked).length;
                const maxSeats = {{ $quantTickets }};
                if (checkedCount > maxSeats) {
                    alert('You can only select up to ' + maxSeats + ' seats.');
                    checkbox.checked = false;
                }
            });
        });
    });
</script>
@endsection
