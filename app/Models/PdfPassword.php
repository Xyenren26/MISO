<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PdfPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'passwords',
        'document_name',
        'ip_address',
        'access_count',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'passwords' => 'array' // Will be manually encrypted
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }

    // Automatically encrypt passwords when setting
    public function setPasswordsAttribute($value)
    {
        $this->attributes['passwords'] = encrypt($value);
    }

    // Automatically decrypt passwords when getting
    public function getPasswordsAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Scope to get only expired records
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Delete all expired PDF password records
     * 
     * @return int Number of deleted records
     */
    public static function deleteExpired()
    {
        try {
            $count = self::expired()->delete();
            Log::info("Deleted {$count} expired PDF password records");
            return $count;
        } catch (\Exception $e) {
            Log::error("Failed to delete expired PDF passwords: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Format duration in seconds to HH:MM:SS format
     * 
     * @param float $seconds
     * @return string
     */
    public static function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}