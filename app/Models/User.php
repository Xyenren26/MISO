<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'first_name',
        'last_name',
        'email',
        'password',
        'department',
        'phone_number',
        'username',
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
            'email_verified_at' => 'datetime',
        ];
        
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
    

}