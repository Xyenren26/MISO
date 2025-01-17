<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\DeviceManagement;
use Illuminate\Support\Facades\Log;

class Home_Controller extends Controller
{
    public function showHome()
    {
        Log::info('Home page access attempt:', ['user' => Auth::user()]);

        // Fetch ticket counts by status
        $ticketCountsByStatus = [
            'in-progress' => Ticket::where('status', 'in-progress')->count(),
            'completed' => Ticket::where('status', 'completed')->count(),
            'endorsed' => Ticket::where('status', 'endorsed')->count(),
            'technical-report' => Ticket::where('status', 'technical-report')->count(),
        ];

        // Fetch pending tickets by priority
        $pendingTickets = Ticket::select('priority', \DB::raw('count(*) as total'))
            ->where('status', 'in-progress')
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray(); // Convert to array for easier manipulation

        // Fetch solved tickets by priority
        $solvedTickets = Ticket::select('priority', \DB::raw('count(*) as total'))
            ->where('status', 'completed')
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Fetch endorsed tickets by priority
        $endorsedTickets = Ticket::select('priority', \DB::raw('count(*) as total'))
            ->where('status', 'endorsed')
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Fetch technical report tickets by priority
        $technicalReports = Ticket::select('priority', \DB::raw('count(*) as total'))
            ->where('status', 'technical-report')
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Prepare data arrays for the charts
        $priorities = ['urgent', 'semi-urgent', 'non-urgent'];
        $pendingData = $this->prepareChartData($priorities, $pendingTickets);
        $solvedData = $this->prepareChartData($priorities, $solvedTickets);
        $endorsedData = $this->prepareChartData($priorities, $endorsedTickets);
        $technicalReportData = $this->prepareChartData($priorities, $technicalReports);

        // Fetch the count of devices in repairs and repaired
        $inRepairsCount = DeviceManagement::where('status', 'in-repairs')->count();
        $repairedCount = DeviceManagement::where('status', 'repaired')->count();

        

        // Return the view with all the data
        return view('home', compact(
            'ticketCountsByStatus', 
            'pendingData', 
            'solvedData', 
            'endorsedData', 
            'technicalReportData', 
            'inRepairsCount', 
            'repairedCount'
        ));
    }

    // Prepare data for charts, ensuring that we match the priorities
    private function prepareChartData($priorities, $data)
    {
        // Normalize and ensure the priorities match (case-insensitive)
        return array_map(function ($priority) use ($data) {
            // Normalize the priority name to lowercase for consistency
            $priority = strtolower($priority);
            return $data[$priority] ?? 0; // Return 0 if priority not found
        }, $priorities);
    }
}
