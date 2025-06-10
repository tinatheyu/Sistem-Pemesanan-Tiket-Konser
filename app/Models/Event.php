<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    
    protected $table = 'events'; // atau concerts, sesuaikan
    protected $fillable = [
        'user_id',
        'nama_konser',
        'jumlah_tiket',
        'kategori',
        'status'
    ];


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
