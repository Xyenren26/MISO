<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;

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
            'solvedTickets' => Ticket::where('status', 'completed')
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
            'devicesInRepair' => ServiceRequest::where('status', 'in-repairs')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'repairedDevices' => ServiceRequest::where('status', 'repaired')
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

        return $technicians->map(function ($tech) use ($ticketData) {
            $techTickets = $ticketData->where('technical_support_id', $tech->employee_id);

            return (object) [
                'first_name' => $tech->first_name,
                'last_name' => $tech->last_name,
                'employee_id' => $tech->employee_id,
                'tickets_assigned' => $techTickets->count() ?: 'None',
                'tickets_solved' => $techTickets->where('status', 'completed')->count() ?: 'None',
                'endorsed_tickets' => $techTickets->where('status', 'endorsed')->count() ?: 'None',
                'technical_reports' => $techTickets->where('status', 'technical-report')->count() ?: 'None',
                'pull_out' => $techTickets->where('status', 'pull-out')->count() ?: 'None',
                'avg_resolution_time' => $techTickets->where('status', 'completed')
                    ->whereNotNull('updated_at')
                    ->map(fn($ticket) => $ticket->updated_at->diffInHours($ticket->created_at))
                    ->average() ?: '0',
            ];
        });
    }

    private function getTechnicianChartData($technicians, $year, $month)
    {
        $chartData = [];
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

        foreach ($technicians as $tech) {
            $dailyTickets = Ticket::selectRaw('DAY(created_at) as day, COUNT(*) as count')
                ->where('technical_support_id', $tech->employee_id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->pluck('count', 'day')
                ->toArray();

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

        return !empty($chartData) ? $chartData : [];
    }
}
