<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyAcceptJsonMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->expectsJson()) {
            //return response(['message' => 'Only JSON requests are allowed' , 'success' => false], 406);
        }

        return $next($request);
    }
}
