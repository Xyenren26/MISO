<?php

// app/Models/EquipmentDescription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_no', 'equipment_type', 'brand', 'remarks',
    ];

    // Define the relationship to the EquipmentPart model
    public function equipmentParts()
    {
        return $this->hasMany(EquipmentPart::class);
    }
}
