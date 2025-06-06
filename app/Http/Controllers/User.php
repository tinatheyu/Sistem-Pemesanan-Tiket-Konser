<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'age' => $request->age,
            'address' => $request->address,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json($user);
    }

    public function login(Request $request)
    {
        $user = User::where('name', $request->name)->first();
        // Tambahkan logika verifikasi password dll
        return response()->json($user);
    }
}