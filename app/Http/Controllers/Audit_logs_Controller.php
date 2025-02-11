<?php

namespace App\Http\Controllers;

use App\Models\Audit_logs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Audit_logs_Controller extends Controller
{
    public function showAudit_logs(Request $request){
        $query = Audit_logs::query();

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_time', [$request->start_date, $request->end_date]);
        }
    
        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }
    
       // Filter by user role (end_user, technical_support)
    if ($request->filled('user')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('account_type', $request->user);
        });
    }
    
        // Search by ticket/device ID or user name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_or_device_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('performed_by', 'LIKE', "%{$request->search}%")
                  ->orWhere('remarks', 'LIKE', "%{$request->search}%");
            });
        }
    
        $auditLogs = $query->orderBy('date_time', 'desc')->paginate(10);
    
        return view('audit_logs', compact('auditLogs'));
    }
}
