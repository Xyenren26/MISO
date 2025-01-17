<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\DeviceManagement;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Home_Controller extends Controller
{
    // Other code...

    public function showHome(Request $request)
    {
        Log::info('Home page access attempt:', ['user' => Auth::user()]);

        // Get the current month or selected month
        $selectedMonth = Carbon::parse($request->get('month', Carbon::now()->format('Y-m')))->format('m');
        $daysOfMonth = range(1, Carbon::createFromFormat('m', $selectedMonth)->daysInMonth);

        // Fetch ticket counts by status for the currently logged-in user
        $ticketCountsByStatus = [
            'in-progress' => Ticket::whereMonth('created_at', $selectedMonth)
                ->where('status', 'in-progress')
                ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
                ->count(),
            'completed' => Ticket::whereMonth('created_at', $selectedMonth)
                ->where('status', 'completed')
                ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
                ->count(),
            'endorsed' => Ticket::whereMonth('created_at', $selectedMonth)
                ->where('status', 'endorsed')
                ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
                ->count(),
            'technical-report' => Ticket::whereMonth('created_at', $selectedMonth)
                ->where('status', 'technical-report')
                ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
                ->count(),
        ];

        // Fetch pending tickets by priority for the currently logged-in user
        $pendingTickets = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->where('status', 'in-progress')
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Fetch solved tickets by priority for the currently logged-in user
        $solvedTickets = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->where('status', 'completed')
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Fetch endorsed tickets by priority for the currently logged-in user
        $endorsedTickets = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->where('status', 'endorsed')
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Fetch technical report tickets by priority for the currently logged-in user
        $technicalReports = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->where('status', 'technical-report')
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
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

        // Fetch the count of devices in repairs and repaired for the currently logged-in user
        $inRepairsCount = DeviceManagement::whereMonth('created_at', $selectedMonth)
            ->where('status', 'in-repairs')
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->count();
        $repairedCount = DeviceManagement::whereMonth('created_at', $selectedMonth)
            ->where('status', 'repaired')
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->count();

        // Fetch daily ticket performance for solved and technical reports with month filter for the logged-in user
        $dailySolvedTickets = Ticket::select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as total'))
            ->where('status', 'completed')
            ->whereMonth('created_at', $selectedMonth)
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        $dailyTechnicalReports = Ticket::select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as total'))
            ->where('status', 'technical-report')
            ->whereMonth('created_at', $selectedMonth)
            ->where('technical_support_id', Auth::user()->employee_id) // Filter by logged-in user
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        // Prepare data for the ticket performance graph
        $solvedDataByDay = $this->prepareChartDataForDays($daysOfMonth, $dailySolvedTickets);
        $technicalReportDataByDay = $this->prepareChartDataForDays($daysOfMonth, $dailyTechnicalReports);

        // Return the view with all the data
        return view('home', compact(
            'ticketCountsByStatus',
            'pendingData',
            'solvedData',
            'endorsedData',
            'technicalReportData',
            'inRepairsCount',
            'repairedCount',
            'solvedDataByDay',
            'technicalReportDataByDay',
            'selectedMonth'
        ));
    }

    // Prepare data for charts, ensuring that we match the priorities
    private function prepareChartData($priorities, $data)
    {
        return array_map(function ($priority) use ($data) {
            return $data[strtolower($priority)] ?? 0;
        }, $priorities);
    }

    // Helper function to prepare chart data by day (filling missing days with 0)
    private function prepareChartDataForDays($daysOfMonth, $dailyData)
    {
        // Dynamically fill the data array based on the number of days in the selected month
        $dataByDay = array_fill_keys($daysOfMonth, 0);

        foreach ($dailyData as $day => $count) {
            if (isset($dataByDay[$day])) {
                $dataByDay[$day] = $count;
            }
        }

        // Return the final array for chart display
        return array_values($dataByDay);
    }
}

