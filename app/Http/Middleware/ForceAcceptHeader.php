<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceAcceptHeader
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->headers->get('accept') !== 'application/json') {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
