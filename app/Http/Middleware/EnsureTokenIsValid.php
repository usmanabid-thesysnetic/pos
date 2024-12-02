<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized: Invalid or expired token'], Response::HTTP_UNAUTHORIZED);
        }
        
        $request->merge(['user' => Auth::guard('sanctum')->user()]);

        return $next($request);
    }
}