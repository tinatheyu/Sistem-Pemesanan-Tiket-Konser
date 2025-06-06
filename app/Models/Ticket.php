<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'pembayaran_id');
    }
}
