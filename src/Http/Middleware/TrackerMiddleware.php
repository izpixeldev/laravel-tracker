<?php

namespace Izpixel\LaravelTracker\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Izpixel\LaravelTracker\Facades\LaravelTracker;
use Symfony\Component\HttpFoundation\Response;

class TrackerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        LaravelTracker::collect();
    }
}
