<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceManagement extends Model
{
    use HasFactory;

    protected $table = 'device_managements';

    protected $fillable = [
        'control_no',
        'name',
        'device',
        'status',
        'technical_support_id',
        'date',
    ];
}

