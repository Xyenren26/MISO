<?php

// app/Models/EquipmentPart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_description_id', 'part_name', 'is_present',
    ];
}
