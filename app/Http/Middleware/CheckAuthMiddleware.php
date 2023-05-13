<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! auth()->guard()->check()){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
