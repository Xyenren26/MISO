<?php

// app/Models/ServiceRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'form_no'; // Set form_no as the primary key
    protected $keyType = 'string'; // Primary key type is string

    protected $fillable = [
        'form_no', 'service_type', 'name', 'employee_id', 'department', 'condition', 'status', 'technical_support_id',
    ];

    public function equipmentDescriptions()
    {
        return $this->hasMany(EquipmentDescription::class, 'form_no', 'form_no');
    }
    
    public function technicalSupport()
    {
        return $this->belongsTo(User::class, 'technical_support_id');
    }

}
