<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Ticket;

class PaymentController extends Controller
{
    public function payTicket(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tiket_id' => 'required|exists:tickets,id',
            'bukti_pembayaran' => 'required|string',
        ]);

        // Buat data pembayaran
        $payment = Payment::create([
            'user_id' => $validated['user_id'],
            'tiket_id' => $validated['tiket_id'],
            'bukti_pembayaran' => $validated['bukti_pembayaran'],
        ]);

        // Update tiket dengan ID pembayaran
        $ticket = Ticket::find($validated['tiket_id']);
        $ticket->pembayaran_id = $payment->id;
        $ticket->save();

        // Kembalikan response JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful and ticket updated',
            'payment' => $payment,
            'ticket' => $ticket,
        ], 201);
    }
}
