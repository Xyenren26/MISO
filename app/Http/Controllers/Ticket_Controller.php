<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket; // Ensure the Ticket model exists

class Ticket_Controller extends Controller
{
    public function showTicket()
    {
        // Fetch tickets with pagination (5 per page)
        $tickets = Ticket::paginate(5);

        // Pass tickets to the view
        return view('ticket', compact('tickets'));
    }
}
