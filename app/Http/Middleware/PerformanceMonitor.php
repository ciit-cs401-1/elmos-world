<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start timing
        $startTime = microtime(true);
        
        // Process the request
        $response = $next($request);
        
        // Calculate execution time
        $executionTime = microtime(true) - $startTime;
        $timeInMs = round($executionTime * 1000, 2);
        
        // Log slow requests (over 500ms)
        if ($timeInMs > 500) {
            Log::warning("Slow request: {$request->method()} {$request->path()} - {$timeInMs}ms");
        }
        
        // Add timing header to the response
        $response->headers->set('X-Execution-Time', "{$timeInMs}ms");
        
        return $response;
    }
}
