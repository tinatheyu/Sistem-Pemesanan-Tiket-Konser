<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
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