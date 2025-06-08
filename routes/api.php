<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

//User
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
//Penyelenggara
Route::post('/events', [EventController::class, 'createEvent']);
//Admin
Route::patch('/events/{id}/approve', [EventController::class, 'approveEvent']);
//User
Route::post('/tickets', [TicketController::class, 'buyTicket']);
//User
Route::post('/payments', [PaymentController::class, 'payTicket']);

// routes/api.php

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

