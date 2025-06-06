<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payTicket(Request $request)
    {
        $payment = Payment::create([
            'user_id' => $request->user_id,
            'tiket_id' => $request->tiket_id,
            'bukti_pembayaran' => $request->bukti_pembayaran,
        ]);

        $ticket = Ticket::find($request->tiket_id);
        $ticket->pembayaran_id = $payment->id;
        $ticket->save();

        return response()->json([
            'payment' => $payment,
            'ticket' => $ticket,
        ]);
    }
}
