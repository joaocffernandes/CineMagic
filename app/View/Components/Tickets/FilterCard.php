<?php

namespace App\View\Components\Tickets;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

// Nao funciona ainda
class FilterCard extends Component
{
    public array $listMovies;
    public array $listDates;

    public function __construct(
        public array $movies,
        public string $filterAction,
        public string $resetUrl,
        public ?string $movie = null,
        public ?string $date = null
    )
    {
        // Assumes movies are passed as ['id' => 'title'] pairs
        $this->listMovies = ['' => 'Any movie'] + $movies;
        $this->listDates = ['' => 'Any date']; // Dates need to be populated dynamically or passed as an array
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tickets.filter-card');
    }
}
