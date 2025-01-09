<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TicketForm extends Component
{
    public $nextControlNo;

    /**
     * Create a new component instance.
     *
     * @param mixed $nextControlNo
     */
    public function __construct($nextControlNo = null)
    {
        $this->nextControlNo = $nextControlNo ?? $this->generateNextControlNo();
    }

    /**
     * Generate the next control number (mocked for example).
     *
     * @return int
     */
    protected function generateNextControlNo()
    {
        // Replace with logic to fetch/generate the next control number
        return 1001; // Example value
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.ticket-form');
    }
}


