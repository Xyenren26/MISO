<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Endorsement extends Model
{
    use HasFactory;

    // Define the table name (optional if it matches the model's plural name)
    protected $table = 'endorsements';

    // Define the primary key
    protected $primaryKey = 'control_no';

    // Specify that the primary key is not an incrementing integer
    public $incrementing = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'control_no',
        'ticket_id',
        'department',
        'network',
        'network_details',
        'endorsed_to',
        'endorsed_to_date',
        'endorsed_to_time',
        'endorsed_to_remarks',
        'endorsed_by',
        'endorsed_by_date',
        'endorsed_by_time',
        'endorsed_by_remarks',
    ];

    // Define the type cast for specific attributes, especially date and time
    protected $casts = [
        'endorsed_to_date' => 'date',
        'endorsed_by_date' => 'date',
    ];

    // Define the relationship to the Ticket model
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    // 🔹 Model Events for Audit Logging
    protected static function boot()
    {
        parent::boot();

        static::created(function ($endorsement) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'created',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $endorsement->control_no,
                'remarks' => 'Endorsement request created',
            ]);
        });

        static::updated(function ($endorsement) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'updated',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $endorsement->control_no,
                'remarks' => 'Endorsement request updated',
            ]);
        });

        static::deleted(function ($endorsement) {
            Audit_logs::create([
                'date_time' => now(),
                'action_type' => 'archive',
                'performed_by' => Auth::user()->employee_id ?? 'System',
                'ticket_or_device_id' => $endorsement->control_no,
                'remarks' => 'Endorsement request archive',
            ]);
        });
    }
}