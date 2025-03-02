<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $primaryKey = 'control_no';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'name',
        'department',
        'priority',
        'concern',
        'remarks',
        'technical_support_id',
        'technical_support_name',
        'status',
        'time_in',
        'time_out',
        'created_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function technicalSupport()
    {
        return $this->belongsTo(TechnicalSupport::class, 'technical_support_id', 'id');
    }

    public function history()
    {
        return $this->hasMany(TicketHistory::class, 'ticket_id', 'control_no');
    }

    public function approval() {
        return $this->hasOne(Approval::class, 'ticket_id', 'control_no');
    }

    public function serviceRequest()
    {
        return $this->hasOne(ServiceRequest::class, 'ticket_id', 'control_no');
    }
    
    public function deployment()
    {
        return $this->hasOne(Deployment::class, 'control_number', 'control_no');
    }

    public function ratings()
    {
        return $this->hasOne(Rating::class, 'control_no', 'control_no');
    }


    // 🔹 Model Events for Audit Logging
    protected static function boot()
    {
        parent::boot();

        static::created(function ($ticket) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'created',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $ticket->control_no,
                'remarks' => 'Ticket created'
            ]);
        });

        static::updated(function ($ticket) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'updated',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $ticket->control_no,
                'remarks' => 'Ticket updated'
            ]);
        });

        static::deleted(function ($ticket) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'deleted',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $ticket->control_no,
                'remarks' => 'Ticket deleted'
            ]);
        });
    }
}
