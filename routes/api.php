<?php

use Illuminate\Http\Request;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\GamesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('dosen', DosenController::class);
Route::apiResource('mahasiswa', MahasiswaController::class);
// Route middleware auth basic
Route::middleware('auth.basic')->apiResource('dosen', DosenController::class);
// Route::middleware('api.key')
Route::middleware('api.key')->get('/data', function () {
    return response()->json([
        'status' => true,
        'message' => 'Access granted',
        'data' => 'Your protected data via API Key', // Sedikit modifikasi pesan
    ]);
});

// Route public (tidak perlu login)
Route::post('/login', [AuthController::class, 'login']);

// Route yang memerlukan otentikasi JWT (protected routes)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']); // Contoh ambil data user

    // Contoh protected route lainnya
    Route::get('/protected-data', function () {
        return response()->json([
            'message' => 'This is protected data!',
            'user' => Auth::guard('api')->user() // Ambil data user yang login
        ]);
    });

    // Anda bisa menambahkan route API resource atau route lain di sini
    // contoh: Route::apiResource('posts', PostController::class);
});

// Contoh route API biasa (jika ada)
// Route::get('/ping', function() {
//     return ['pong' => true];
// });