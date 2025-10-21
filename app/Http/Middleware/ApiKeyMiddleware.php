<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->query('key');
        
        if (!$token || !$this->validateToken($token)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid or missing API token'
            ], 401);
        }

        return $next($request);
    }

    private function validateToken(string $token): bool
    {
        $validTokens = config('api.valid_tokens', ['secret123']);
        return in_array($token, $validTokens);
    }
}