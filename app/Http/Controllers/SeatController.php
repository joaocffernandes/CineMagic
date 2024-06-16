<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SeatController extends Controller
{
    public function show(Screening $screening, int $quantTickets)
    {
        $screeningDatetime = Carbon::createFromFormat('Y-m-d H:i:s', $screening->date . ' ' . $screening->start_time);
        if ($screeningDatetime->addMinutes(5)->lt(now())) {
            return back()->with('alert-msg', 'This screening session has already started more than 5 minutes ago.')
                         ->with('alert-type', 'danger');
        }

        $seatsDisabledList = Ticket::where('screening_id', $screening->id)
                                    ->pluck('seat_id')->toArray();

        $cartSeats = collect(session('cart', []))
                        ->where('screening_id', $screening->id)
                        ->pluck('seat_id')->toArray();

        $seatsDisabledList = array_unique(array_merge($seatsDisabledList, $cartSeats));

        return view('seats.show', compact('screening', 'quantTickets', 'seatsDisabledList'));
    }
}
