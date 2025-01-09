<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit_logs extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'date_time',
        'action_type',
        'performed_by',
        'ticket_or_device_id',
        'remarks',
    ];
}
