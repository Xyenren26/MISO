<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Ticket;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Report_Controller extends Controller
{
    public function showReport(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now()->format('Y-m'));

        // Validate selected date format (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedDate)) {
            $selectedDate = Carbon::now()->format('Y-m'); // Reset if invalid
        }

        [$year, $month] = explode('-', $selectedDate);

        $technicians = User::where('account_type', 'technical_support')->get();

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
                'tickets.technical_support_name',
                'tickets.time_in',
                'tickets.time_out',
                'ratings.rating',
                'ratings.remark',
                \DB::raw('(ratings.rating * 20) as rating_percentage'),
                \DB::raw('TIMESTAMPDIFF(SECOND, tickets.time_in, tickets.time_out) as duration_seconds') // Calculate duration in seconds
            )
            ->whereBetween('tickets.created_at', [$startOfWeek, $endOfWeek]) // Filter for the current week
            ->paginate(10);

        // Format the duration for each ticket
        $ticketRecords->transform(function ($ticket) {
            $ticket->duration = $this->formatDuration($ticket->duration_seconds);
            return $ticket;
        });


        return view('Report', [
            'pendingTickets' => Ticket::where('status', 'in-progress')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'solvedTickets' => Ticket::whereIn('status', ['completed', 'pull-out', 'deployment'])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'endorsedTickets' => Ticket::where('status', 'endorsed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'technicalReports' => Ticket::where('status', 'technical-report')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'technicians' => $this->getTechnicianData($technicians, $year, $month),
            'technicianChartData' => $this->getTechnicianChartData($technicians, $year, $month),
            'selectedDate' => $selectedDate,
            'ticketRecords' => $ticketRecords 
        ]);
    }

    private function getTechnicianData($technicians, $year, $month)
    {
        $ticketData = Ticket::whereIn('technical_support_id', $technicians->pluck('employee_id'))
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return $technicians->map(function ($tech) use ($ticketData, $year, $month) {
            $techTickets = $ticketData->where('technical_support_id', $tech->employee_id);

            // Fetch ratings and calculate the monthly average rating
            $averageRating = Rating::where('technical_support_id', $tech->employee_id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->avg('rating');

            return (object) [
                'first_name' => $tech->first_name,
                'last_name' => $tech->last_name,
                'employee_id' => $tech->employee_id,
                'tickets_assigned' => $techTickets->count() ?: 'None',
                'tickets_solved' => $techTickets->where('status', 'completed')->count() ?: 'None',
                'endorsed_tickets' => $techTickets->where('status', 'endorsed')->count() ?: 'None',
                'technical_reports' => $techTickets->where('status', 'technical-report')->count() ?: 'None',
                'pull_out' => $techTickets->where('status', 'pull-out')->count() ?: 'None',
                'rating' => $averageRating ? number_format(($averageRating / 5) * 100, 1) . '%' : 'No Rating'
            ];
        });
    }

    private function getTechnicianChartData($technicians, $year, $month)
    {
        \Log::info("Fetching chart data for Year: $year, Month: $month");

        $chartData = [];
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

        foreach ($technicians as $tech) {
            // Fetch daily ticket count
            $dailyTickets = Ticket::selectRaw('DAY(created_at) as day, COUNT(*) as count')
                ->where('technical_support_id', $tech->employee_id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->pluck('count', 'day')
                ->toArray();

            \Log::info("Technician {$tech->employee_id} - Daily Tickets: ", $dailyTickets);

            if (empty($dailyTickets)) {
                \Log::warning("No ticket records found for Technician {$tech->employee_id}");
            }

            $ticketsPerDay = array_fill(1, $daysInMonth, 0);
            foreach ($dailyTickets as $day => $count) {
                $ticketsPerDay[$day] = $count;
            }

            $chartData[] = [
                'label' => "{$tech->first_name} {$tech->last_name} ({$tech->employee_id})",
                'data' => array_values($ticketsPerDay),
                'borderColor' => sprintf("#%06X", mt_rand(0, 0xFFFFFF)),
                'backgroundColor' => sprintf("rgba(%d,%d,%d,0.2)", rand(0, 255), rand(0, 255), rand(0, 255)),
                'fill' => true
            ];
        }

        \Log::info('Final Chart Data:', $chartData);

        return !empty($chartData) ? $chartData : [];
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
                'tickets.technical_support_name',
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
                'tickets.technical_support_name',
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

    private function formatDuration($seconds)
    {
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

    public function exportTechnicianPerformance(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m')); // Default to current month

        // Extract year and month
        [$year, $month] = explode('-', $selectedMonth);

        // Fetch only technical support users
        $technicians = User::where('account_type', 'technical_support')
            ->with([
                'ratings' => function ($query) use ($year, $month) {
                    $query->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month);
                }
            ])
            ->get();

        // Fetch ticket data for selected month
        $ticketData = Ticket::whereIn('technical_support_id', $technicians->pluck('employee_id'))
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // Define CSV header
        $csvData = "Technician Name / ID, Tickets Assigned, Tickets Solved, Endorsed Tickets, Pull Out Device, Technical Reports Submitted, Rating\n";

        // Populate the CSV with technician data
        foreach ($technicians as $technician) {
            $techTickets = $ticketData->where('technical_support_id', $technician->employee_id);

            // Calculate performance metrics
            $ticketsAssigned = $techTickets->count() ?: 'None';
            $ticketsSolved = $techTickets->where('status', 'completed')->count() ?: 'None';
            $endorsedTickets = $techTickets->where('status', 'endorsed')->count() ?: 'None';
            $pullOut = $techTickets->where('status', 'pull-out')->count() ?: 'None';
            $technicalReports = $techTickets->where('status', 'technical-report')->count() ?: 'None';

            // Calculate average rating and convert to percentage
            $averageRating = $technician->ratings->avg('rating');
            $ratingPercentage = $averageRating ? number_format(($averageRating / 5) * 100, 1) . '%' : 'No Rating';

            // Append row data
            $csvData .= "{$technician->first_name} {$technician->last_name} / {$technician->employee_id},"
                . "$ticketsAssigned,"
                . "$ticketsSolved,"
                . "$endorsedTickets,"
                . "$pullOut,"
                . "$technicalReports,"
                . "$averageRating\n";
        }

        // Return CSV as a response
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=TechnicianPerformance_{$selectedMonth}.csv");
    }
}