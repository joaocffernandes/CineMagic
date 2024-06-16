<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Purchase;

class PurchaseReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;
    public $purchaseReceiptPath;
    public $ticketOutputs;

    public function __construct($purchase, $purchaseReceiptPath, $ticketOutputs)
    {
        $this->purchase = $purchase;
        $this->purchaseReceiptPath = $purchaseReceiptPath;
        $this->ticketOutputs = $ticketOutputs;
    }

    public function build()
    {
        $email = $this->view('purchases.show', ['purchase' => $this->purchase]) // An view for the client see easier the purchase without download pdf
                      ->subject('Your Purchase Receipt and Tickets');

        // Still sending the pdf version for donwload 
        $purchaseOutput = Storage::get($this->purchaseReceiptPath);
        $email->attachData($purchaseOutput, "purchase_receipt.pdf",  ['mime' => 'application/pdf']);

        foreach ($this->ticketOutputs as $index => $ticketOutput) {
            $email->attachData($ticketOutput, "ticket-{$index}.pdf",  ['mime' => 'application/pdf']);
        }

        return $email;
    }
}
