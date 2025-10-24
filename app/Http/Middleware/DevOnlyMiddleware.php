<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DevOnlyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Only allow aliuwahab@gmail.com to access logs
        if (auth()->user()->email !== 'aliuwahab@gmail.com') {
            abort(403, 'Developer access required.');
        }

        return $next($request);
    }
}
