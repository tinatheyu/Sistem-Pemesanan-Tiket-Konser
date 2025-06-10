<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use OpenApi\Annotations as OA;
class UserController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "age", "address", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="age", type="integer", example=25),
 *             @OA\Property(property="address", type="string", example="Jl. Mawar No. 123"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="secret123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="User registered successfully"),
 *             @OA\Property(property="token", type="string", example="a1b2c3d4e5f6..."),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Validation failed"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Registration failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Registration failed"),
 *             @OA\Property(property="error", type="string", example="Unexpected error message")
 *         )
 *     )
 * )
 */

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'age' => 'required|integer',
                'address' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

             $user = User::create([
                'name' => $request->name,
                'age' => $request->age,
                'address' => $request->address,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'token' => Str::random(60), // Token disimpan saat register
        ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $user->token,
                'data' => $user
        ], 201);
        }  

        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
        ], 400);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'name' => $user->name,
                'role' => $user->role,
                'age' => $user->age,
                'address' => $user->address
            ]   
        ], 200);
        $user = User::where('name', $request->name)->first();
        // Tambahkan logika verifikasi password dll
        
        return response()->json($user);
    }

            public function profile(Request $request)
            {
                $token = $request->bearerToken();

                // Tidak kirim token = langsung tolak
                if (!$token) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Token is required'
                    ], 401);
                }

                // Cari user dengan token tsb
                $user = User::where('token', $token)->first();

                if (!$user) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid token'
                    ], 401);
                }

                 $user = $request->user; // didapat dari middleware
                // Token valid
                return response()->json([
                    'status' => 'success',
                    'message' => 'Authenticated user',
                    'data' => [
                        'name' => $user->name,
                        'role' => $user->role,
                        'age' => $user->age,
                        'address' => $user->address
                    ]
                ], 200);
            }




}