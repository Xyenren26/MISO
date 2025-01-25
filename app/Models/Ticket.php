<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'tickets';

    // Specify the primary key for the table
    protected $primaryKey = 'control_no';


    public $incrementing = false;

    // Specify the primary key type
    protected $keyType = 'string';

    // Mass-assignable attributes
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
        'created_at',
    ];

    // Timestamps (created_at and updated_at) are enabled by default
    public $timestamps = true;

    /**
     * Define a relationship with the Employee model.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Define a relationship with the Technical Support model.
     */
    public function technicalSupport()
    {
        return $this->belongsTo(TechnicalSupport::class, 'technical_support_id', 'id');
    }
    public function history()
    {
        return $this->hasMany(TicketHistory::class, 'ticket_id', 'control_no');
    }    

}
