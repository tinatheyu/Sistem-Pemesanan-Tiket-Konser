<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Ticket;

class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/payments",
     *     summary="Process ticket payment",
     *     description="Create payment record and update ticket with payment ID",
     *     tags={"Payment"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "tiket_id", "bukti_pembayaran"},
     *             @OA\Property(property="user_id", type="integer", description="User ID"),
     *             @OA\Property(property="tiket_id", type="integer", description="Ticket ID"),
     *             @OA\Property(property="bukti_pembayaran", type="string", description="Payment proof")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Payment successful and ticket updated"),
     *             @OA\Property(property="payment", type="object"),
     *             @OA\Property(property="ticket", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
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
