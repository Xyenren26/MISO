<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concern extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'default_priority',
        'assigned_user_id',
        'assign_to_all_tech'
    ];

    public function children()
    {
        return $this->hasMany(Concern::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Concern::class, 'parent_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}