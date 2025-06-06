<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function buyTicket(Request $request)
    {
        $ticket = Ticket::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json($ticket);
    }
}