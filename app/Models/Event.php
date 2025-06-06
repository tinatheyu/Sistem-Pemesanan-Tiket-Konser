<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    
    protected $table = 'events'; // atau concerts, sesuaikan

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
