<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WantsJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->header('Accept') === 'application/json' &&
            $request->header('Content-Type') === 'application/json'
        ) {
            return $next($request);
        }

        abort(400, 'Wrong headers. Add "Accept: application/json" and "Content-Type: application/json"', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }
}
