<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Auth;

class ApiKeyMiddleware
{
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('API_KEY');
        $authenticatedUser = Auth::user();

        if (!empty($apiKey) && is_null($authenticatedUser)) {
            $apiKeyRecord = ApiKey::where('key', $apiKey)->first();

            if (!$apiKeyRecord) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $next($request);
        } elseif (!empty($apiKey) && !is_null($authenticatedUser)) {
            // Gunakan autentikasi
            return $next($request);
        } elseif (is_null($apiKey) && !is_null($authenticatedUser)) {
            // Gunakan autentikasi
            return $next($request);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
