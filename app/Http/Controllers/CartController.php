<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Ticket;
use App\Models\Purchase;
use App\Services\PurchaseService;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Configuration;
use App\Services\Payment;
use Carbon\Carbon;


class CartController extends Controller
{
    public function show(): View
    {
        $cart = session('cart', []);
        return view('cart.show', compact('cart'));
    }

    public function createTicketAndAddToCart(Request $request): RedirectResponse 
    {
        $screeningId = $request->input('screening');
        $seatIds = $request->input('seats');
    
        if ($seatIds == null || empty($seatIds)) {
            return back()->with('alert-msg', "You must select an seat")->with('alert-type', 'danger');
        }

        if ($screeningId == null) {
            return redirect()->route('screenings.index')->with('alert-msg', "You must select the screening")->with('alert-type', 'danger');
        }

        $screening = Screening::findOrFail($screeningId);
        $seats = Seat::findMany($seatIds);

        $bookedSeats = Ticket::where('screening_id', $screeningId)->whereIn('seat_id', $seatIds)->exists();
        if ($bookedSeats) {
            return redirect()->route('screenings.index')->with('alert-msg', 'One or more selected seats are already booked.')->with('alert-type', 'danger');
        }
    
        // Discount for registered users
        $config = Configuration::getSettings();
        if (auth()->check()) {
            $price = $config->ticket_price - $config->customer_ticket_discount;
        } else {
            $price = $config->ticket_price;
        }
    
        $tickets = collect();
    
        foreach ($seats as $seat) {
            $tickets->push(Ticket::make([
                'purchase_id' => null,
                'screening_id' => $screening->id,
                'seat_id' => $seat->id,
                'price' => $price,
                'status' => 'valid',
                'qrcode_url' => null
            ]));
        }
    
        return $this->addToCart($tickets);
    }

    private function addToCart($tickets): RedirectResponse
    {
        $cart = session('cart', null);

        foreach ($tickets as $ticket) {
            // Verifica se o tempo atual é mais do que 5 minutos após o início da sessão
            $screeningDatetime = Carbon::createFromFormat('Y-m-d H:i:s', $ticket->screening->date . ' ' . $ticket->screening->start_time);
            if ($screeningDatetime->addMinutes(5)->lt(now())) {
                return redirect()->route('screenings.index')->with('alert-msg', 'This screening session has already started more than 5 minutes ago.')
                                 ->with('alert-type', 'danger');
            }

            $exists = $cart?->contains(function ($value) use ($ticket) {
                return $value['screening_id'] == $ticket->screening_id && $value['seat_id'] == $ticket->seat_id;
            });
            
            if (!$cart) {
                $cart = collect([$ticket]);
                session()->put('cart', $cart);
            } else { 
                if ($exists) {
                    $alertType = 'warning';
                    $htmlMessage = "Ticket <strong>\"{$ticket->screening->movie->title}\"</strong> was not added to the cart because it is already there!";
                    return redirect()->route('screenings.index')
                        ->with('alert-msg', $htmlMessage)
                        ->with('alert-type', $alertType);
                } else {
                    $cart->push($ticket);
                }
            }
        }

        $alertType = 'success';
        $htmlMessage = "Ticket(s) <strong>\"{$tickets->first()->screening->movie->title}\"</strong> were added to the cart.";
        return redirect()->route('screenings.index')
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart($screeningId, $seatId)
    {
        $tickets = session('cart', collect());
    
        $ticketIndex = $tickets->search(function ($ticket) use ($screeningId, $seatId) {
            return $ticket->screening_id == $screeningId && $ticket->seat_id == $seatId;
        });
    
        if ($ticketIndex !== false) {
            $ticket = $tickets->pull($ticketIndex);
    
            if($tickets->count() > 0) {
                session(['cart' => $tickets]);
            } else {
                session()->forget('cart');
            }
    
            $alertMessage = "Ticket for <strong>\"{$ticket->screening->movie->title}\"</strong> was removed from the cart.";
    
            return back()->with('alert-msg', $alertMessage)->with('alert-type', 'success');
        } else {
            return back()->with('alert-msg', "Ticket not found in the cart.")->with('alert-type', 'warning');
        }
    }

    public function checkout()
    {
        $cart = session('cart', collect());
        if ($cart->isEmpty()) {
            return redirect()->route('cart.show')->with([
                'alert-type' => 'warning',
                'alert-msg' => 'Your shopping cart is empty. Please add some tickets before proceeding to checkout.'
            ]);
        }
    
        $user = auth()->user();
        $customer = $user?->customer;
    
        return view('checkout.show', [
            'cart' => $cart, 
            'customer' => $customer, 
            'user' => $user
        ]);
    }

    public function confirm(Request $request)
    {
        $cart = session('cart', collect());
        if ($cart->isEmpty()) {
            return back()->with('alert-msg', 'Your cart is empty.')->with('alert-type', 'warning');
        }

        $this->validateParams($request);
        $paymentResult = $this->processPayment($request->all());

        foreach ($cart as $ticket) 
        {
            $bookedSeats = Ticket::where('screening_id', $ticket->screening->id)->where('seat_id', $ticket->seat_id)->exists();
            if ($bookedSeats) {
                return back()->with('alert-msg', 'Some seats are already booked, sorry :(')->with('alert-type', 'danger');
            }
        }

        if ($paymentResult !== false) {
            $purchase = DB::transaction(function () use ($cart, $request) {
                $purchase = Purchase::create([
                    'customer_id' => auth()->user()->customer->id ?? null,
                    'total_price' => $cart->sum('price'),
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'nif' => $request->nif,
                    'payment_type' => $request->payment_type,
                    'payment_ref' => $request->payment_ref,
                    'date' => now()->toDateString(),
                    'receipt_pdf_filename' => null
                ]);

                foreach ($cart as $ticket) {

                    // Hard url for qrcode_url
                    $randomString = Str::random(40);
                    $hash = hash('sha256', $ticket->screening_id . $ticket->seat_id . $randomString); 
                    $ticket->qrcode_url = url("/tickets/validate/{$ticket->screening_id}/{$ticket->seat_id}/{$hash}"); 
                
                    $ticket->purchase_id = $purchase->id;
                    $ticket->status = 'valid';
                    $ticket->save();
                }

                $purchase->receipt_pdf_filename = PurchaseService::createPdfOfPurchase($purchase, $cart);
                $purchase->save();

                PurchaseService::sendPurchaseReceiptWithTickets($purchase);
                session()->forget('cart');
                return $purchase;
            });

            if (auth()->check()) 
            {
                return redirect()->route('purchases.show', ['purchase' => $purchase->id])
                    ->with('alert-msg', 'Purchase completed successfully.')->with('alert-type', 'success');
            } 
            else 
            {
                return redirect()->route('screenings.index')->with('alert-type', 'success')->with('alert-msg', 'Purchase completed successfully. The receipt was sent to your email.');
            }

        } else {
            return back()->with('alert-msg', 'Payment failed. Please try again.')->with('alert-type', 'danger');
        }
    }

    private function validateParams($request) {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|email',
            'nif' => 'required|digits:9',
            'payment_type' => 'required|string|in:VISA,PAYPAL,MBWAY',
        ];


        if ($request->payment_type == 'MBWAY'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|regex:/^9\d{8}$/',
            ]);
        }

        if ($request->payment_type == 'PAYPAL'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|string|email|max:255',
            ]);
        }

        if ($request->payment_type == 'VISA'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|regex:/^4[0-9]{15}$/',
                'cvc' => 'required|regex:/^[0-9]{2}[013-9]$/'
            ]);
        }

        $request->validate($rules);
    } 

    private function processPayment($data)
    {
        switch ($data['payment_type']) {
            case 'VISA':
                return Payment::payWithVisa($data['payment_ref'], $data['cvc']);
            case 'PAYPAL':
                return Payment::payWithPaypal($data['payment_ref']);
            case 'MBWAY':
                return Payment::payWithMBway($data['payment_ref']);
            default:
                return false;
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }    
}
