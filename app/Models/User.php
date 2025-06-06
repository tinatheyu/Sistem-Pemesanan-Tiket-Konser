<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'age',
        'address',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke Event (konser)
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Relasi ke Ticket (tiket yang dibeli)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Relasi ke Payment (pembayaran)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
