<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    //KOko
    public function createEvent(Request $request)
    {
        $event = Event::create([
            'user_id' => $request->user_id,
            'nama_konser' => $request->nama_konser,
            'jumlah_tiket' => $request->jumlah_tiket,
            'kategori' => $request->kategori,
            'status' => 'pending',
        ]);
        return response()->json($event);
    }
    public function getEvent($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }
    //sampek kene koko
    public function approveEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'approved';
        $event->save();
        return response()->json($event);
    }
    
    public function rejectEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'rejected';
        $event->save();
        return response()->json($event);
    }
    
    
}
