<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use PDF; // dompdf
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PurchaseReceipt;
use App\Services\PurchaseService;
use FontLib\TrueType\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PurchaseController extends Controller
{
    public function index()
    {
        if (auth()->user()->type == 'A') 
        {
            $purchases = Purchase::paginate(10);
        } 
        else if (auth()->user()->customer) 
        {
            $purchases = Purchase::with(['tickets', 'tickets.screening', 'tickets.screening.movie'])
                ->where('customer_id', auth()->user()->customer->id) 
                ->orderByDesc('created_at')
                ->paginate(10);
        } 
        else 
        {
            $purchases = collect(); // Do not show any items of purchase
        }
    
        return view('purchases.index', compact('purchases'));
    }

    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);
        if (!$purchase) 
        {
            return redirect()->route('purchases.index')->with('alert-msg', 'Purchase not found.')->with('alert-type', 'warning');
        }

        if ($purchase->customer->id != auth()->id()) 
        {
            return redirect()->route('purchases.index')->with('alert-msg', 'You do not have permission to view this purchase.')->with('alert-type', 'danger');
        }

        return view('purchases.show', compact('purchase'));
    }

    public function downloadReceipt(Purchase $purchase)
    {
        if ($purchase->customer->id == auth()->id() || auth()->user()->type == 'A')  
        {
            $pdfPath = $purchase->receipt_pdf_filename;
            if (!Storage::exists($pdfPath)) {
                abort(404, 'The receipt file does not exist.');
            }
        
            $pdfContent = Storage::get($pdfPath);
            $contentType = 'application/pdf';
            $fileName = 'Receipt-' . $purchase->id . '.pdf';
        
            return response($pdfContent, 200)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        }

        return back()->with('alert-msg', 'You do not have permission to download this purchase.')->with('alert-type', 'danger');
    }

    public function resendEmail(Purchase $purchase) {
        if (auth()->user()->type == 'A') 
        {
            PurchaseService::sendPurchaseReceiptWithTickets($purchase);
            return back()->with('alert-msg', 'Success resending email to customer')->with('alert-type', 'success');
        }

        return back()->with('alert-msg', 'You do not have permission to send emails.')->with('alert-type', 'danger');
    }
}
