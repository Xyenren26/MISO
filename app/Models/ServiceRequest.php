<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'form_no';
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'ticket_id', 'form_no', 'service_type', 'name', 'employee_id', 
        'department', 'condition', 'status', 'technical_support_id',
    ];

    public function equipmentDescriptions()
    {
        return $this->hasMany(EquipmentDescription::class, 'form_no', 'form_no');
    }

    public function technicalSupport()
    {
        return $this->belongsTo(User::class, 'technical_support_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'control_no');
    }

    // 🔹 Model Events for Audit Logging
    protected static function boot()
    {
        parent::boot();

        static::created(function ($request) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'created',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $request->form_no,
                'remarks' => 'Service request created'
            ]);
        });

        static::updated(function ($request) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'updated',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $request->form_no,
                'remarks' => 'Service request updated'
            ]);
        });

        static::deleted(function ($request) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'deleted',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $request->form_no,
                'remarks' => 'Service request deleted'
            ]);
        });
    }
}
