<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $apiKey = $request->header('API_KEY'); // Mendapatkan API key dari header API_KEY
        $apiKeyRecord = ApiKey::where('key', $apiKey)->first();
// dd($apiKeyRecord);
        if ($apiKeyRecord) {
            // Memeriksa peran dari data pengguna yang terkait dengan API key
            $userRole = $apiKeyRecord->user->role;

            if ($userRole == $role) {
                return $next($request);
            }
        } else {
            // Jika API key tidak ditemukan, lanjutkan dengan autentikasi normal
            $userRole = auth()->user()->role;

            if ($userRole == $role) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
