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
    try {
        $event = Event::findOrFail($id);
          if (!$event) {
            return response()->json([
                'code' => 404,
                'message' => 'Event not found.'
            ], 404);
        }
        if ($event->status == 'approved') {
            return response()->json([
                'code' => 400,
                'message' => 'Event already approved.'
            ], 400);
        }
        $event->status = 'approved';
        $event->save();
        return response()->json([
            'code' => 200,
            'message' => 'Event approved successfully.',
            'data' => $event
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Failed to approve event.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
   public function rejectEvent($id)
{
    try {
        $event = Event::findOrFail($id);
        if (!$event) {
            return response()->json([
                'code' => 404,
                'message' => 'Event not found.'
            ], 404);
        }
        if ($event->status == 'rejected') {
            return response()->json([
                'code' => 400,
                'message' => 'Event already rejected.'
            ], 400);
        }
        $event->status = 'rejected';
        $event->save();

        return response()->json([
            'code' => 200,
            'message' => 'Event rejected successfully.',
            'data' => $event
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Failed to reject event.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
    
}
