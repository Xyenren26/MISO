<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Illuminate\Auth\Notifications\ResetPassword;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $primaryKey = 'employee_id';
    protected $fillable = [
        'employee_id',
        'id',
        'name',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'department',
        'phone_number',
        'account_type',
        'status',
        'is_first_login',
        'last_activity',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the name of the column used for authentication.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'employee_id'; // Tell Laravel to use username as the unique identifier
    }

    /**
     * Get the password for the authentication.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
        
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'technical_support_id', 'employee_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }

    public function ticketsAsPreviousSupport()
    {
        return $this->hasMany(TicketHistory::class, 'previous_technical_support', 'employee_id');
    }

    public function ticketsAsNewSupport()
    {
        return $this->hasMany(TicketHistory::class, 'new_technical_support', 'employee_id');
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function pdfPasswords()
    {
        return $this->hasMany(\App\Models\PdfPassword::class, 'employee_id', 'employee_id');
    }

    public function getLatestPdfPassword()
    {
        return $this->pdfPasswords()
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function isCurrentlyActive()
    {
        // Method 1: Check active session (recommended)
        return \DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(config('auth.lifetime', 120)))
            ->exists();

        // OR Method 2: Check remember token (alternative)
        // return $this->remember_token && $this->last_seen_at > now()->subMinutes(30);
    }
}