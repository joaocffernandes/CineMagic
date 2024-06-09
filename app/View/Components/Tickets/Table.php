<?php

namespace App\View\Components\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public object $tickets;
    public bool $showView;
    public bool $showEdit;
    public bool $showDelete;
    public bool $showAddToCart;
    public bool $showRemoveFromCart;

    public function __construct(
        $tickets,
        $showView = true,
        $showEdit = true,
        $showDelete = true,
        $showAddToCart = true,
        $showRemoveFromCart = true
    ) {
        $this->tickets = $tickets;
        $this->showView = $showView;
        $this->showEdit = $showEdit;
        $this->showDelete = $showDelete;
        $this->showAddToCart = $showAddToCart;
        $this->showRemoveFromCart = $showRemoveFromCart;
    }

    public function render(): View
    {
        return view('components.tickets.table');
    }
}
