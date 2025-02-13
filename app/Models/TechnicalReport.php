<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalReport extends Model
{
    use HasFactory;

    protected $table = 'technical_reports';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'control_no',
        'date_time',
        'department',
        'enduser',
        'specification',
        'problem',
        'workdone',
        'findings',
        'recommendation',
        'reported_by',
        'reported_date',
        'inspected_by',
        'inspected_date',
        'noted_by',
        'noted_date',
    ];
    protected $casts = [
        'date_time' => 'datetime',
        'reported_date' => 'datetime',
        'inspected_date' => 'datetime',
        'noted_date' => 'datetime',
    ];
    /**
     * Relationship to the Ticket model
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'control_no', 'control_no');
    }
}
