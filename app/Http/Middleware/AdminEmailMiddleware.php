<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminEmailMiddleware
{
    protected $allowedEmail = 'emmanuelreydelmercado@gmail.com';

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->email !== $this->allowedEmail) {
            abort(403, 'Access denied. This area is restricted to administrators.');
        }

        return $next($request);
    }
}
