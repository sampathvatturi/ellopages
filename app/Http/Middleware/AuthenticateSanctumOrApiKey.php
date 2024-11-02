<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class AuthenticateSanctumOrApiKey
{
    public function handle(Request $request, Closure $next)
    {
        // Check if a Sanctum token is provided
        if ($request->bearerToken()) {
            // Attempt to authenticate with Sanctum
            $accessToken = PersonalAccessToken::findToken($request->bearerToken());
            if ($accessToken && $accessToken->tokenable) {
                Auth::login($accessToken->tokenable);
                return $next($request); // User is authenticated via Sanctum
            }
        }

        // If no Sanctum token, fall back to checking the API key
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey === X_API_KEY) {
            return $next($request); // Authorized by API key
        }

        // If neither method succeeded, deny access
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
