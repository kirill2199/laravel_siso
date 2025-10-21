<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next)
    {
        $validKey = config('app.api_key');
        $providedKey = $request->query('key');

        if (!$providedKey || $providedKey !== $validKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}