<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Mail\PurchaseReceipt;
use App\Http\Requests\DisciplineFormRequest;
use App\Models\Ticket;
use App\Models\Movie;
use App\Models\Purchase;
use App\Services\QrCodeService;
use Illuminate\View\View;
use PDF; // dompdf
use Mpdf\Mpdf; // mpdf

use App\Models\Screening;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $ticketsQuery = Ticket::with(['screening.movie', 'screening.theater', 'seat', 'purchase']);
                            //->whereHas('screening', function ($query) { $query->where('date', '>=', now()->format('Y-m-d'));  })

        if (auth()->user()->type != 'A')  
        {
            $ticketsQuery->whereHas('purchase', function ($query) {
                $customerId = auth()->user()->customer->id;  // Garante que está autenticado e tem um cliente associado
                $query->where('customer_id', $customerId);
            });
        }
                            
        $ticketsQuery->orderBy('created_at', 'desc');

        
        $movieIds = $ticketsQuery->get()->pluck('screening.movie_id')->unique()->filter();
        $movies = Movie::whereIn('id', $movieIds)->orderBy('title', 'asc')->pluck('title', 'id');
        
    
        if ($request->filled('purchase')) {
            $purchaseId = $request->purchase;
            $ticketsQuery->whereHas('purchase', function ($query) use ($purchaseId) {
                $query->where('id', $purchaseId);
            });
        } else {
            if ($request->filled('movie')) {
                $ticketsQuery->whereHas('screening.movie', function ($query) use ($request) {
                    $query->where('id', $request->movie);
                });
            }
        
            if ($request->filled('date')) {
                $ticketsQuery->whereHas('screening', function ($query) use ($request) {
                    $query->where('date', $request->date);
                });
            }
        }
    
        $tickets = $ticketsQuery->paginate(20)->withQueryString();
        $purchase = $request->purchase ?? null;
            
        return view('tickets.index', compact('tickets', 'movies', 'purchase'));
    }

    /**
     * Display the specified resource.
     */
    public function showByTicket(Ticket $ticket)
    {
        if ($ticket->purchase->customer) 
        {
            if (auth()->id() === $ticket->purchase->customer->id || auth()->user()->type == 'A') 
            {
                return view('tickets.show')
                    ->with('ticket', $ticket);
            }
        }

        return back()->with('alert-msg', 'You do not have permission to view this ticket.')->with('alert-type', 'danger');
    }

    public function showBySession(int $screeningId, int $seatId)
    {
        $tickets = session('cart', collect());
    
        $ticketIndex = $tickets->search(function ($ticket) use ($screeningId, $seatId) {
            return $ticket->screening_id == $screeningId && $ticket->seat_id == $seatId;
        });
    
        if ($ticketIndex !== false) {
            $ticket = $tickets->get($ticketIndex);
            
            return view('tickets.show', compact('ticket'));
        }
    
        return back()->with('alert-msg', 'Ticket do not exist.')->with('alert-type', 'warning');
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

    public function downloadReceipt(Ticket $ticket)
    {
        if (auth()->id() === $ticket->purchase->customer->id || auth()->user()->type == 'A') 
        {
            $qrImageSrc = QrCodeService::getQrCodeImageBase64($ticket->qrcode_url);
        
            $html = view('emails.tickets.receipt', compact('ticket', 'qrImageSrc'))->render();
        
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            $fileName = 'Receipt-' . $ticket->id . '.pdf';
            $mpdf->Output($fileName, 'D'); // 'D' for download
            return back();
        }

        return back()->with('alert-msg', 'You do not have permission to donwload this ticket or it does not exist.')->with('alert-type', 'danger');
    }
    
    public function showReceipt(Ticket $ticket) // For test
    {    
        if (auth()->id() === $ticket->purchase->customer->id || auth()->user()->type == 'A') 
        {
            $qrImageSrc = QrCodeService::getQrCodeImageBase64($ticket->qrcode_url);
            return view('emails.tickets.receipt', compact('ticket', 'qrImageSrc'));
        }

        return back()->with('alert-msg', 'You do not have permission to view this ticket or it does not exist.')->with('alert-type', 'danger');
    }
}
