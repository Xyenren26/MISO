<?php

namespace App\Http\Controllers;

use App\Models\Audit_logs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Audit_logs_Controller extends Controller
{
    public function showAudit_logs(Request $request){
        // Apply filters
        $query = Audit_logs::query();

        // Filter by date range
        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('date_time', [
                $request->input('start_date'),
                $request->input('end_date'),
            ]);
        }

        // Filter by action type
        if ($request->has('action_type')) {
            $query->where('action_type', $request->input('action_type'));
        }

        // Filter by user
        if ($request->has('performed_by')) {
            $query->where('performed_by', $request->input('performed_by'));
        }

        // Search by Ticket or Device ID
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_or_device_id', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('remarks', 'like', '%' . $request->input('search') . '%');
            });
        }

        // Paginate results
        $auditLogs = $query->with('user')->paginate(10);

        // Return view with logs
        return view('audit_logs', compact('auditLogs'));

    }
}
