<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Deployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_number', 'purpose', 'status', 'components', 'software',
        'brand_name', 'specification', 'received_by', 'received_date', 
        'issued_by', 'issued_date',
    ];

    protected $casts = [
        'components' => 'array',
        'software' => 'array',
    ];

    public function equipmentItems()
    {
        return $this->hasMany(EquipmentItem::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'control_no', 'control_number');
    }


    // 🔹 Model Events for Audit Logging
    protected static function boot()
    {
        parent::boot();

        static::created(function ($deployment) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'created',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $deployment->control_number,
                'remarks' => 'Deployment record created'
            ]);
        });

        static::updated(function ($deployment) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'updated',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $deployment->control_number,
                'remarks' => 'Deployment record updated'
            ]);
        });

        static::deleted(function ($deployment) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'deleted',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $deployment->control_number,
                'remarks' => 'Deployment record deleted'
            ]);
        });
    }
}
