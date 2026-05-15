<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'account_status',
        'verification_token',
        'verification_token_expires_at',
        'id_rol',
        'two_factor_secret',
        'two_factor_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones
    public function groomer()
    {
        return $this->hasOne(Groomer::class, 'id_usuario');
    }

    public function cajero()
    {
        return $this->hasOne(Cajero::class, 'id_usuario');
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'id_usuario');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_usuario');
    }

    public function generateVerificationToken()
    {
        $this->verification_token = \Illuminate\Support\Str::random(60);
        $this->verification_token_expires_at = now()->addMinutes(15);
        $this->save();
        
        return $this->verification_token;
    }
}