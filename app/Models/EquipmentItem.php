<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentItem extends Model
{
    use HasFactory;

    // Define fillable properties for mass assignment
    protected $fillable = [
        'deployment_id', 'description', 'serial_number', 'quantity'
    ];

    // Relationship with Deployment
    public function deployment()
    {
        return $this->belongsTo(Deployment::class);
    }
}
