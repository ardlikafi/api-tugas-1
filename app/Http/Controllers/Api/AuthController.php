<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Pilih mau login pakai email atau username
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Sesuaikan credentials berdasarkan input login
        $credentials = $request->only('email', 'password');
        if (! $token = Auth::guard('api')->attempt($credentials)) { // Gunakan $credentials
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

        if (! $token = Auth::guard('api')->attempt($credentials)) { // Gunakan $credentials
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6', // 'confirmed' berarti harus ada field 'password_confirmation'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => Hash::make($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function me()
    {
        try {
            $user = Auth::guard('api')->userOrFail();
            return response()->json($user);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
             return response()->json(['error' => 'User not found or token invalid'], 404);
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            $newToken = Auth::guard('api')->refresh();
            return $this->respondWithToken($newToken);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Jika token tidak bisa direfresh (misalnya sudah di blacklist atau invalid)
            return response()->json(['error' => 'Could not refresh token, please login again'], 401);
        }
    }

    protected function respondWithToken($token)
    {
        // Ambil TTL dari konfigurasi JWT (config/jwt.php)
        $ttl = config('jwt.ttl') * 60; // TTL di config adalah menit, ubah ke detik

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl
        ]);
    }
}