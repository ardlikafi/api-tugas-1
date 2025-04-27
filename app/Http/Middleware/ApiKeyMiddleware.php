<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 
class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response 
    {
        // Ambil API Key dari header 'D4MI-API-KEY'
        $apiKey = $request->header('D4MI-API-KEY');

        // Bandingkan dengan API Key dari file .env
        if ($apiKey !== env('API_KEY')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Invalid API Key',
            ], 401); // Kode status 401 Unauthorized
        }

        // Jika valid, lanjutkan request
        return $next($request);
    }
}