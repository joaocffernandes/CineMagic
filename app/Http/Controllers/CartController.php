<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Ticket;

class CartController extends Controller
{
    public function show(): View
    {
        $cart = session('cart', []);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request, Ticket $ticket): RedirectResponse
    {
        $cart = session('cart', null);
        if (!$cart) {
            $cart = collect([$ticket]);
            $request->session()->put('cart', $cart);
        } else { 
            if ($cart->firstWhere('id', $ticket->id)) {
            $alertType = 'warning';
            $url = route('tickets.show', ['ticket' => $ticket]);
            $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> <strong>\"{$ticket->screening->movie->title}\"</strong> was not added to the cart because it is already there!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
            } else {
                $cart->push($ticket);
            }
        }

        $alertType = 'success';
        $url = route('tickets.show', ['ticket' => $ticket]);
        $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> <strong>\"{$ticket->screening->movie->title}\"</strong> was added to the cart.";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, Ticket $ticket): RedirectResponse
    {
        $url = route('tickets.show', ['ticket' => $ticket]);
        $cart = session('cart', null);
        if (!$cart) {
            $alertType = 'warning';
            $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> was not removed from the cart because cart is empty!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $element = $cart->firstWhere('id', $ticket->id);
            if ($element) {
                $cart->forget($cart->search($element));
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
                }
                $alertType = 'success';
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> was removed from the cart.";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a> was not removed from the cart because cart does not include it!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
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
