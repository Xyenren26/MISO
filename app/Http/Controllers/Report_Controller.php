<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Ticket;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;


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

    
    public function exportTechnicianPerformancePDF(Request $request) {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
    
        $technicians = User::with([
            'ratings' => function ($query) use ($selectedMonth) {
                $query->whereMonth('created_at', Carbon::parse($selectedMonth)->month)
                      ->whereYear('created_at', Carbon::parse($selectedMonth)->year);
            }
        ])->get();
    
        $pdf = Pdf::loadView('pdf.technician_performance', compact('technicians', 'selectedMonth'));
    
        return $pdf->download("TechnicianPerformance_{$selectedMonth}.pdf");
    }
    public function exportTechnicianPerformance(Request $request) {
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
