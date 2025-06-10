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
Route::post('/logout', [UserController::class, 'logout']);
//Penyelenggara
Route::post('/events', [EventController::class, 'createEvent']);
Route::get('/events/{id}', [EventController::class,'getEvent']);
Route::delete('/events/{id}', [EventController::class, 'deleteEvent']);
Route::put('/events/{id}', [EventController::class, 'updateEvent']);

//Admin
Route::patch('/events/{id}/approve', [EventController::class, 'approveEvent']);
Route::patch('/events/{id}/reject', [EventController::class, 'rejectEvent']);
//User
Route::post('/tickets', [TicketController::class, 'buyTicket']);
Route::get('/tickets/{id}', [TicketController::class, 'getTicketDetail']);

//Payment
Route::post('/payments', [PaymentController::class, 'payTicket']);
Route::get('/profile', [UserController::class, 'profile']);

//Logout
// Route::post('/logout', [UserController::class, 'logout']);


