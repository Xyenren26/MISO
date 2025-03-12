<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model {
    use HasFactory;

    protected $fillable = ['control_no', 'technical_support_id', 'remark','rating'];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'control_no', 'control_no');
    }

    public function technicalSupport() {
        return $this->belongsTo(User::class, 'technical_support_id', 'employee_id');
    }
}
