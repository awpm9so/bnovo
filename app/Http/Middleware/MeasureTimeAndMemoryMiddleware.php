<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeasureTimeAndMemoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->withHeaders([
            'X-Debug-Time' => (microtime(true) - LARAVEL_START) * 0.001,
            'X-Debug-Memory' => memory_get_peak_usage() / 1024,
        ]);

        return $response;
    }
}
