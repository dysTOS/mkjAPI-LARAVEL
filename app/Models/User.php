<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Uuid, CanResetPassword;

    /**
     * @var mixed
     */
    // private $id;

    public function mitglied()
    {
        return $this->belongsTo(Mitglieder::class, 'user_id');
    }

        /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = env('FE_URL').'/reset-password?token='.$token.'&email='.$this->email;
        $this->notify(new ResetPasswordNotification($url));
    }

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'passwort'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'passwort',
        'remember_token',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
