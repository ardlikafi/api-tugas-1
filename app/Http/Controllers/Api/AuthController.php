<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Jangan lupa import User model

class AuthController extends Controller
{
    /**
     * Membuat instance AuthController baru.
     * Menerapkan middleware auth:api kecuali untuk method login.
     *
     * @return void
     */
    public function __construct()
    {
        // Terapkan middleware 'auth:api' (yang menggunakan guard 'api' dengan driver 'jwt')
        // Kecuali untuk method 'login'
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Proses login user dan kembalikan JWT token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6', // Sesuaikan min jika perlu
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // Unprocessable Entity
        }

        // Coba otentikasi menggunakan kredensial yang divalidasi
        // Kita gunakan Auth::guard('api') karena kita mengkonfigurasi guard 'api' untuk JWT
        if (! $token = Auth::guard('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401); // Unauthorized
        }

        // Jika berhasil, kembalikan token
        return $this->respondWithToken($token);
    }

    /**
     * Dapatkan detail user yang sedang login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Dapatkan user yang terotentikasi melalui guard 'api'
        try {
            $user = Auth::guard('api')->userOrFail();
            return response()->json($user);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
             return response()->json(['error' => 'User not found or token invalid'], 404);
        }
    }

    /**
     * Proses logout user (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout(); // Invalidate token untuk guard 'api'

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh token. User harus sudah login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Refresh token untuk guard 'api'
        try {
            $newToken = Auth::guard('api')->refresh();
            return $this->respondWithToken($newToken);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    /**
     * Format response dengan token JWT.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $ttl = Auth::guard('api')->factory()->getTTL() * 60; // Dapatkan TTL (Time To Live) dalam detik

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl // Waktu kadaluarsa token dalam detik
        ]);
    }
}