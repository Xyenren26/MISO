<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    use HasFactory;

    // Define fillable properties for mass assignment
    protected $fillable = [
        'control_number', 'purpose', 'status', 'components', 'software',
        'brand_name', 'specification', 'received_by', 'received_date', 
        'issued_by', 'issued_date', 'noted_by', 'noted_date'
    ];

    // Cast JSON fields to array
    protected $casts = [
        'components' => 'array',
        'software' => 'array',
    ];

    // Relationship with EquipmentItem
    public function equipmentItems()
    {
        return $this->hasMany(EquipmentItem::class);
    }
}
