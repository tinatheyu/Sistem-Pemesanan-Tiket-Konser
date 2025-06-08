<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function buyTicket(Request $request)
    {
        $ticket = Ticket::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json($ticket);

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil dibeli',
            'data' => $ticket
        ]);
    }
    }
    