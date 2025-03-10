<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketArchive extends Model
{
    use HasFactory;

    protected $table = 'ticket_archives';
    protected $primaryKey = 'control_no';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'control_no',
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
        'archived_at'
    ];
}
