<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use OpenApi\Annotations as OA;
class EventController extends Controller
{
   /**
 * @OA\Post(
 *     path="/api/events",
 *     summary="Create a new concert event",
 *     description="Creates a new event with pending status.",
 *     operationId="createEvent",
 *     tags={"Events"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","nama_konser","jumlah_tiket","kategori"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user creating the event"),
 *             @OA\Property(property="nama_konser", type="string", example="Konser Musik Indie", description="Name of the concert"),
 *             @OA\Property(property="jumlah_tiket", type="integer", example=100, description="Total available tickets"),
 *             @OA\Property(property="kategori", type="string", example="Musik", description="Category of the event")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Event created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=10),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="nama_konser", type="string", example="Konser Musik Indie"),
 *                 @OA\Property(property="jumlah_tiket", type="integer", example=100),
 *                 @OA\Property(property="kategori", type="string", example="Musik"),
 *                 @OA\Property(property="status", type="string", example="pending"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-10T12:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-10T12:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Something went wrong"),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[23000]: Integrity constraint violation...")
 *         )
 *     )
 * )
 */

   public function createEvent(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'nama_konser' => 'required|string',
                'jumlah_tiket' => 'required|integer',
                'kategori' => 'required|string',    
            ]);

            $event = Event::create([
                'user_id' => $validated['user_id'],
                'nama_konser' => $validated['nama_konser'],
                'jumlah_tiket' => $validated['jumlah_tiket'],
                'kategori' => $validated['kategori'],
                'status' => 'pending'
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $event
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Create Event Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
 *  * @OA\Delete(
 *     path="/api/events/{id}",
 *     summary="Delete an event by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the event to delete"
 *     ),

 *     @OA\Response(
 *         response=200,
 *         description="Event successfully deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Event berhasil dihapus")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Event not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Event tidak ditemukan")
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
   public function deleteEvent($id)
{
    $event = Event::find($id);

    if (!$event) {
        return response()->json(['message' => 'Event tidak ditemukan'], 404);
    }

    $event->delete();

    return response()->json(['message' => 'Event berhasil dihapus']);
}

     /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Get Event detail",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getEvent($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    
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


    
   public function rejectEvent($id){
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
}
