<?php

// app/Services/PurchaseService.php

namespace App\Services;

use App\Mail\PurchaseReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Purchase;
use Mpdf\Mpdf; // mpdf

class PurchaseService
{
    public static function createPdfOfPurchase(Purchase $purchase, $tickets)
    {
        $html = view('emails.purchases.receipt', compact('purchase', 'tickets'))->render();
            
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4', 
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 2,
            'margin_bottom' => 10,
            'margin_header' => 9,
            'margin_footer' => 9
        ]);
    
        $pdf->WriteHTML($html);
    
        $pdfPath = 'receipts/purchases/' . $purchase->id . '.pdf';
        $pdfOutput = $pdf->Output('', 'S');  // 'S' for string
        Storage::put($pdfPath, $pdfOutput);

        return $pdfPath;
    }

    public static function sendPurchaseReceiptWithTickets(Purchase $purchase)
    {
        $ticketsPdfOutputs = [];
        foreach ($purchase->tickets as $ticket) {
     
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4', 
            ]);

            $qrImageSrc = QrCodeService::getQrCodeImageBase64($ticket->qrcode_url);
            $htmlTicket = view('emails.tickets.receipt', compact('ticket', 'qrImageSrc'))->render();
            $mpdf->WriteHTML($htmlTicket);
            try {
                $output = $mpdf->Output('','S');
            } catch (\Exception $e) {  
                dump($e->getMessage());
                dump($mpdf);
                if (isset($output)) {
                    dd($output);  
                } else {
                    dd('Output not defined due to an error.');  
                }
            }
            $ticketsPdfOutputs[] = $output; // 'S' for string for not save in disk
        }
        
        Mail::to($purchase->customer_email)->send(new PurchaseReceipt($purchase, $purchase->receipt_pdf_filename, $ticketsPdfOutputs));
    }
}
