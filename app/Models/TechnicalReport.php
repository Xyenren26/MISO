<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalReport extends Model
{
    use HasFactory;

    protected $table = 'technical_reports';

    protected $primaryKey = 'TR_id'; // Ensure this matches your database
    protected $keyType = 'string'; // If TR_id is a string
    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'TR_id',
        'control_no',
        'date_time',
        'department',
        'enduser',
        'specification',
        'problem',
        'workdone',
        'findings',
        'recommendation',
        'inspected_by',
        'inspected_date',
    ];
    protected $casts = [
        'date_time' => 'datetime',
        'inspected_date' => 'datetime',
    ];
    /**
     * Relationship to the Ticket model
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'control_no', 'control_no');
    }
}
