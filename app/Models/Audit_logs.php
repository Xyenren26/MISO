<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit_logs extends Model
{
    use HasFactory;

    protected $table = 'audit_logs'; // Ensure this matches your table name

    protected $fillable = [
        'date_time', 
        'action_type', 
        'performed_by', 
        'ticket_or_device_id', 
        'remarks',
    ];

    protected $casts = [
        'date_time' => 'datetime', // Ensure date_time is properly cast
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by', 'employee_id');
    }

}
