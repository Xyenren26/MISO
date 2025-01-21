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
    public function showHome(Request $request)
    {
        Log::info('Home page access attempt:', ['user' => Auth::user()]);
    
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

        // Pending tickets by priority
        $pendingTickets = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'in-progress')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Solved tickets by priority
        $solvedTickets = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'completed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Endorsed tickets by priority
        $endorsedTickets = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'endorsed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Technical reports by priority
        $technicalReports = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'technical-report')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();

        // Chart data preparation
        $priorities = ['urgent', 'semi-urgent', 'non-urgent'];
        $pendingData = $this->prepareChartData($priorities, $pendingTickets);
        $solvedData = $this->prepareChartData($priorities, $solvedTickets);
        $endorsedData = $this->prepareChartData($priorities, $endorsedTickets);
        $technicalReportData = $this->prepareChartData($priorities, $technicalReports);

        // Device management counts
        $inRepairsCount = DeviceManagement::whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'in-repairs')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->count();
        $repairedCount = DeviceManagement::whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'repaired')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->count();

        // Daily ticket performance
        $dailySolvedTickets = Ticket::select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'completed')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        $dailyTechnicalReports = Ticket::select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as total'))
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'technical-report')
            ->where('technical_support_id', Auth::user()->employee_id)
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        // Prepare data for daily performance charts
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
            'selectedMonth',
            'selectedYear'
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
}
