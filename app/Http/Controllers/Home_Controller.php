<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class Home_Controller extends Controller
{
    public function showHome(Request $request)
    {
        // Get the current or selected month and year
        $selectedDate = $request->get('month', Carbon::now()->format('Y-m')); // Format: YYYY-MM
        $selectedMonth = Carbon::parse($selectedDate)->format('m'); // Extract the month
        $selectedYear = Carbon::parse($selectedDate)->format('Y');  // Extract the year
    
        // Days in the selected month
        $daysOfMonth = range(1, Carbon::createFromDate($selectedYear, $selectedMonth)->daysInMonth);
    
        // Ticket counts by status for the logged-in user
        $ticketCountsByStatus = [
            'in-progress' => Ticket::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->where('status', 'in-progress')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->count(),
            'completed' => Ticket::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->where('status', 'completed')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->count(),
            'endorsed' => Ticket::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->where('status', 'endorsed')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->count(),
            'technical-report' => Ticket::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->where('status', 'technical-report')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->count(),
        ];
    
        // Normalize priority values to lowercase
        $pendingTickets = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'in-progress')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        $solvedTickets = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'completed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        $endorsedTickets = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'endorsed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        $technicalReports = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'technical-report')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        // Chart data preparation
        $priorities = ['urgent', 'high', 'medium', 'low'];
        $pendingData = $this->prepareChartData($priorities, $pendingTickets);
        $solvedData = $this->prepareChartData($priorities, $solvedTickets);
        $endorsedData = $this->prepareChartData($priorities, $endorsedTickets);
        $technicalReportData = $this->prepareChartData($priorities, $technicalReports);
    
        // Service request counts
        $inRepairsCount = ServiceRequest::whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'in-repairs')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->count();
    
        $repairedCount = ServiceRequest::whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'repaired')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->count();
    
        // Daily ticket performance
        $dailySolvedTickets = Ticket::select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->whereIn('status', ['completed', 'technical-report', 'endorsement', 'deployment', 'pull-out'])
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->get()
            ->pluck('total', 'day')
            ->toArray();
    
        $dailyTechnicalReports = Ticket::select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'in-progress')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->get()
            ->pluck('total', 'day')
            ->toArray();
    
        // Prepare data for daily performance charts
        $solvedDataByDay = $this->prepareChartDataForDays($daysOfMonth, $dailySolvedTickets);
        $technicalReportDataByDay = $this->prepareChartDataForDays($daysOfMonth, $dailyTechnicalReports);
    
        // Get the start and end dates of the current week
        $startOfWeek = now()->startOfWeek()->toDateTimeString(); // Start of the week (Monday)
        $endOfWeek = now()->endOfWeek()->toDateTimeString();     // End of the week (Sunday)
    
        $ticketRecords = DB::table('tickets')
            ->leftJoin('ratings', 'tickets.control_no', '=', 'ratings.control_no')
            ->select(
                'tickets.control_no',
                'tickets.created_at as date_time',
                'tickets.name',
                'tickets.department',
                'tickets.concern',
                'tickets.priority',
                'tickets.status',
                'tickets.remarks',
                'tickets.time_in',
                'tickets.time_out',
                'ratings.rating',
                'ratings.remark',
                \DB::raw('(ratings.rating * 20) as rating_percentage'),
                \DB::raw('TIMESTAMPDIFF(SECOND, tickets.time_in, tickets.time_out) as duration_seconds') // Calculate duration in seconds
            )
            ->whereBetween('tickets.created_at', [$startOfWeek, $endOfWeek]) // Filter for the current week
            ->where('tickets.technical_support_id', Auth::user()->employee_id)
            ->paginate(10);
    
        // Format the duration for each ticket
        $ticketRecords->transform(function ($ticket) {
            $ticket->duration = $this->formatDuration($ticket->duration_seconds);
            return $ticket;
        });
    
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
            'selectedMonth',
            'selectedYear',
            'ticketRecords' // Add this to pass ticket records to the view
        ));
    }
    private function prepareChartData($priorities, $data)
    {
        return array_map(function ($priority) use ($data) {
            return $data[strtolower($priority)] ?? 0;
        }, $priorities);
    }

    private function prepareChartDataForDays($daysOfMonth, $dailyData)
    {
        $dataByDay = array_fill_keys($daysOfMonth, 0);
        foreach ($dailyData as $day => $count) {
            if (isset($dataByDay[$day])) {
                $dataByDay[$day] = $count;
            }
        }
        return array_values($dataByDay);
    }

    private function formatDuration($seconds) {
        if (!$seconds) {
            return 'N/A';
        }
    
        $days = floor($seconds / (3600 * 24));
        $hours = floor(($seconds % (3600 * 24)) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
    
        $duration = '';
        if ($days > 0) {
            $duration .= "{$days} day" . ($days > 1 ? 's ' : ' ');
        }
        if ($hours > 0) {
            $duration .= "{$hours} hour" . ($hours > 1 ? 's ' : ' ');
        }
        if ($minutes > 0) {
            $duration .= "{$minutes} minute" . ($minutes > 1 ? 's' : '');
        }
    
        return $duration ? trim($duration) : '0 minutes';
    }

    public function fetchTickets(Request $request)
    {
        $filter = $request->query('filter');
        $month = $request->query('month');
        $year = $request->query('year');
        $page = $request->query('page', 1); // Default to page 1 if not provided

        // Start the query with a join to the ratings table
        $query = Ticket::leftJoin('ratings', 'tickets.control_no', '=', 'ratings.control_no')
            ->select(
                'tickets.control_no',
                'tickets.created_at as date_time',
                'tickets.name',
                'tickets.department',
                'tickets.concern',
                'tickets.priority',
                'tickets.status',
                'tickets.remarks',
                'tickets.time_in',
                'tickets.time_out',
                'ratings.rating',
                'ratings.remark',
                \DB::raw('(ratings.rating * 20) as rating_percentage'),
                \DB::raw('TIMESTAMPDIFF(SECOND, tickets.time_in, tickets.time_out) as duration_seconds')
            );

        // Apply filtering based on the selected option
        if ($filter === 'weekly') {
            $query->whereBetween('tickets.time_in', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'monthly') {
            $query->whereYear('tickets.time_in', $year)->whereMonth('tickets.time_in', $month);
        } elseif ($filter === 'annually') {
            $query->whereYear('tickets.time_in', $year);
        }

        // Fetch results with pagination
        $tickets = $query->paginate(10);

        // Format the duration for each ticket
        $tickets->transform(function ($ticket) {
            $ticket->duration = $this->formatDuration($ticket->duration_seconds);
            return $ticket;
        });

        return response()->json($tickets);
    } 

    public function exportTickets(Request $request)
    {
        $filter = $request->query('filter');
        $month = $request->query('month');
        $year = $request->query('year');

        $query = Ticket::leftJoin('ratings', 'tickets.control_no', '=', 'ratings.control_no')
            ->select(
                'tickets.control_no',
                'tickets.created_at as date_time',
                'tickets.name',
                'tickets.department',
                'tickets.concern',
                'tickets.priority',
                'tickets.status',
                'tickets.remarks',
                'tickets.time_in',
                'tickets.time_out',
                'ratings.rating',
                'ratings.remark',
                \DB::raw('(ratings.rating * 20) as rating_percentage'),
                \DB::raw('TIMESTAMPDIFF(SECOND, tickets.time_in, tickets.time_out) as duration_seconds')
            );

        // Apply filtering
        if ($filter === 'weekly') {
            $query->whereBetween('tickets.time_in', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'monthly') {
            $query->whereYear('tickets.time_in', $year)->whereMonth('tickets.time_in', $month);
        } elseif ($filter === 'annually') {
            $query->whereYear('tickets.time_in', $year);
        }

        // Fetch all records (no pagination)
        $tickets = $query->get();

        // Format duration
        $tickets->transform(function ($ticket) {
            $ticket->duration = $this->formatDuration($ticket->duration_seconds);
            unset($ticket->duration_seconds); // Remove unnecessary field
            return $ticket;
        });

        return response()->json($tickets);
    }

    public function fetchTicketData(Request $request)
    {
        $selectedDate = $request->get('month', Carbon::now()->format('Y-m'));
        $selectedMonth = Carbon::parse($selectedDate)->format('m');
        $selectedYear = Carbon::parse($selectedDate)->format('Y');
    
        // Normalize priority values to lowercase
        $pendingTickets = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'in-progress')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        $solvedTickets = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'completed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        $endorsedTickets = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'endorsed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        $technicalReports = Ticket::select(DB::raw('LOWER(priority) as priority'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'technical-report')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('LOWER(priority)')) // Group by lowercase priority
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    
        // Prepare data for the response
        $response = [
            'in-progress' => $pendingTickets,
            'completed' => $solvedTickets,
            'endorsed' => $endorsedTickets,
            'technical-report' => $technicalReports,
        ];
    
        return response()->json($response);
    }
}
