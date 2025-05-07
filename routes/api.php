<?php

use Illuminate\Http\Request;
use App\Http\Controllers\DosenController;         
use App\Http\Controllers\MahasiswaController;    
use App\Http\Controllers\GamesController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;    

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// }); // Dikomentari jika tidak pakai Sanctum untuk API ini

// --- Route Group untuk Otentikasi JWT ---
Route::group([
    'middleware' => 'api', // Middleware 'api' dari Laravel (akan menggunakan guard 'api' kita)
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api'); // Butuh token
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api'); // Butuh token
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');       // Butuh token
});


// --- Route yang Diproteksi oleh JWT ---
Route::group(['middleware' => 'auth:api'], function () {
    // Pindahkan resource controller ke sini jika ingin diproteksi JWT
    Route::apiResource('dosen', DosenController::class);
    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::apiResource('games', GamesController::class); 
    Route::get('/protected-data-jwt', function () { 
        return response()->json([
            'message' => 'This is protected data via JWT!',
            'user' => Auth::guard('api')->user()
        ]);
    });
});


// --- Route lain yang mungkin masih ada (misal dengan API Key) ---
Route::middleware('api.key')->get('/data-apikey', function () { // Ganti nama URL sedikit
    return response()->json([
        'status' => true,
        'message' => 'Access granted',
        'data' => 'Your protected data via API Key',
    ]);
});

// Route publik jika ada
Route::get('/public-info', function() {
    return response()->json(['message' => 'This is public information.']);
});