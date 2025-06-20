<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to API routes
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
            
            $response = $next($request);
            
            if (!$response->headers->has('Content-Type')) {
                $response->headers->set('Content-Type', 'application/json');
            }

            if ($response->getStatusCode() === 401) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                    'error' => 'Please login to access this resource'
                ], 401);
            }

            // If the response is a redirect, convert it to JSON error response
            if ($response->isRedirection()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                    'error' => 'Please login to access this resource'
                ], 401);
            }
            
            return $response;
        }

        return $next($request);
    }
}
