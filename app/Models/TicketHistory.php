<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_histories';

    protected $fillable = [
        'ticket_id',
        'previous_technical_support',
        'new_technical_support',
        'changed_at',
    ];

    public $timestamps = true;

    /**
     * Define a relationship with the Ticket model.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
    /**
     * Define a relationship with the User model for the previous technical support.
     */
    public function previousTechnicalSupport()
    {
        return $this->belongsTo(User::class, 'previous_technical_support', 'employee_id');
    }

    /**
     * Define a relationship with the User model for the new technical support.
     */
    public function newTechnicalSupport()
    {
        return $this->belongsTo(User::class, 'new_technical_support', 'employee_id');
    }
   

}
