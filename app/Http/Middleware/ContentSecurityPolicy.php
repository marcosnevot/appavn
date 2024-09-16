<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Establece la directiva Content-Security-Policy
        $response->headers->set('Content-Security-Policy', "connect-src 'self' ws://127.0.0.1:6001 ws://[::1]:6001 wss://127.0.0.1:6001 http://sockjs-mt1.pusher.com");

        return $response;
    }
}
