<?php

use Illuminate\Http\Request;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\GamesController;
use Illuminate\Support\Facades\Route;

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