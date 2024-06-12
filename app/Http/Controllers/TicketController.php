<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DisciplineFormRequest;
use App\Models\Ticket;
use App\Models\Movie;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $filterByMovie = $request->query('movie');
        $filterByDate = $request->query('date');
        
        $ticketsQuery = Ticket::query()->with('screening.movie');

        if ($filterByMovie !== null) {
            $ticketsQuery->whereHas('screening.movie', function ($query) use ($filterByMovie) {
                $query->where('id', $filterByMovie);
            });
        }
        if ($filterByDate !== null) {
            $ticketsQuery->whereHas('screening', function ($query) use ($filterByDate) {
                $query->where('date', $filterByDate);
            });
        }

        $tickets = $ticketsQuery->whereHas('screening')->paginate(20)->withQueryString();
        $movies = Movie::pluck('title', 'id');

        return view('tickets.index', compact('tickets', 'movies', 'filterByMovie', 'filterByDate'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): View
    {
        return view('tickets.show')
            ->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit')
            ->with('ticket', $ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketFormRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update($request->validated());
        $url = route('tickets.show', ['ticket' => $ticket]);
        $htmlMessage = "Ticket <a href='$url'><u>{$ticket->name}</u></a> has been updated successfully!";
        return redirect()->route('tickets.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        try {
            $url = route('tickets.show', ['ticket' => $ticket]);
            if ($ticket->canBeDeleted()) {  // Assume there is a method canBeDeleted that checks the deletion criteria
                $ticket->delete();
                $alertMsg = "Ticket {$ticket->name} has been deleted successfully!";
                $alertType = 'success';
            } else {
                $alertMsg = "Ticket <a href='$url'><u>{$ticket->name}</u></a> cannot be deleted because it is currently in use.";
                $alertType = 'warning';
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the ticket
                            <a href='$url'><u>{$ticket->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('tickets.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
