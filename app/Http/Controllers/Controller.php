<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Sistem Penjualan Tiket Konser API",
 *     version="1.0.0",
 *     description="Dokumentasi API Tiket Konser",
 *     @OA\Contact(
 *         name="Developer",
 *         email="Ticket@example.com"
 *     )
 * )
 * 
 *
 * @OA\SecurityScheme(
 *securityScheme="bearerAuth",
 *in="header",
 *name="bearerAuth",
 *type="http",
 *scheme="bearer",
 *bearerFormat="JWT",
 *),
 */
abstract class Controller {}
