<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'form_no', 'name', 'approve_date', 'noted_by'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function servicerequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'form_no');
    }
}
