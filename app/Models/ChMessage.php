<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_id', // The ID of the sender
        'to_id',   // The ID of the recipient
        'body',    // The message content
        'created_at',
        'updated_at',
    ];
}