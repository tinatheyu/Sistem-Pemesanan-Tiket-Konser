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
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     summary="Delete an event",
     *     description="Deletes an existing event",
     *     operationId="deleteEvent",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event deleted successfully",
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

    
    /**
     * @OA\Patch(
     *     path="/api/events/{id}/approve",
     *     summary="Approve an event",
     *     description="Approves a pending event",
     *     operationId="approveEvent",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to approve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event approved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Event approved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="status", type="string", example="approved"),
     *                 @OA\Property(property="nama_konser", type="string", example="Konser Musik"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Event already approved",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Event already approved.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Event not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Failed to approve event."),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
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
    
    /**
     * @OA\Patch(
     *     path="/api/events/{id}/reject",
     *     summary="Reject an event",
     *     description="Rejects a pending event",
     *     operationId="rejectEvent",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to reject",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event rejected successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Event rejected successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="status", type="string", example="rejected"),
     *                 @OA\Property(property="nama_konser", type="string", example="Konser Musik"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Event already rejected",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Event already rejected.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Event not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Failed to reject event."),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     summary="Update an event",
     *     description="Updates an existing event details",
     *     operationId="updateEvent",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_konser","jumlah_tiket","kategori"},
     *             @OA\Property(property="nama_konser", type="string", example="Konser Rock"),
     *             @OA\Property(property="jumlah_tiket", type="integer", example=200),
     *             @OA\Property(property="kategori", type="string", example="Rock")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Event updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama_konser", type="string", example="Konser Rock"),
     *                 @OA\Property(property="jumlah_tiket", type="integer", example=200),
     *                 @OA\Property(property="kategori", type="string", example="Rock"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Event not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function updateEvent(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);

            $validated = $request->validate([
                'nama_konser' => 'required|string',
                'jumlah_tiket' => 'required|integer',
                'kategori' => 'required|string',
            ]);

            $event->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
                'data' => $event
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'The given data was invalid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    }

