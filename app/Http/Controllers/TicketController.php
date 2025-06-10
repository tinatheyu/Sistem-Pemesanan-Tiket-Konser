<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\User;

class TicketController extends Controller
{
    public function buyTicket(Request $request)
    {
        try {
            // Validasi input dasar dulu (tanpa exists)
            $validated = $request->validate([
                'event_id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);

            // Cek apakah event dan user ada, jika tidak return 404 dengan pesan "Not found"
            if (!Event::where('id', $validated['event_id'])->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not found',
                    'errors' => ['event_id' => ['Not found']]
                ], 404);
            }

            if (!User::where('id', $validated['user_id'])->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not found',
                    'errors' => ['user_id' => ['Not found']]
                ], 404);
            }

            // Buat tiket baru
            $ticket = Ticket::create([
                'event_id' => $validated['event_id'],
                'user_id' => $validated['user_id'],
            ]);

            // Return response JSON dengan pesan dan status success
            return response()->json([
                'status' => 'success',
                'message' => 'Ticket purchased successfully',
                'data' => $ticket
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kalau validasi gagal (input kosong atau bukan angka)
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Tangani error lainnya
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to purchase ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTicketDetail($id)
    {
        $ticket = Ticket::with(['event', 'user'])->find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket detail fetched successfully',
            'ticket' => $ticket
        ]);
    }

}
