<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketArchive;

class ArchiveController extends Controller
{
    // Display archived tickets
    public function index(Request $request)
    {
        $query = TicketArchive::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('control_no', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('department', 'like', "%$search%");
        }

        $archivedTickets = $query->paginate(10)->onEachSide(1);

        return view('archive', compact('archivedTickets'));
    }
}
