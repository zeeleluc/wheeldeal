<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (!auth()->check() || 'admin' !== auth()->user()->role) {
            return redirect()->route('welcome');
        }

        return $next($request);
    }
}
