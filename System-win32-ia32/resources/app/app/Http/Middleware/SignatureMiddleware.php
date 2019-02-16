<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $headername = 'X-name')
    {
        $response = $next($request);

        $response->headers->set($headername, config('app.name'));

        return $response;
    }
}
