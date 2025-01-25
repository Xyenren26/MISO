<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'department',
        'network',
        'network_details',
        'user_account',
        'user_account_details',
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
        'endorsed_to_time' => 'time',
        'endorsed_by_date' => 'date',
        'endorsed_by_time' => 'time',
    ];

    // Optionally, you can define relationships if needed, for example:
    // public function userAccount() {
    //     return $this->belongsTo(User::class, 'user_account');
    // }
}