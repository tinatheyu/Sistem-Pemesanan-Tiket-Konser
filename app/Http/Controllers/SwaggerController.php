<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwaggerController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get user info",
 *     @OA\Response(
 *         response=200,
 *         description="Success"
 *     )
 * )
 */
public function getUser()
{
    return response()->json(['name' => 'ppq', 'status' => 'ganteng']);
}

}
