<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/events', [EventController::class, 'createEvent']);
Route::patch('/events/{id}/approve', [EventController::class, 'approveEvent']);

Route::post('/tickets', [TicketController::class, 'buyTicket']);

Route::post('/payments', [PaymentController::class, 'payTicket']);
