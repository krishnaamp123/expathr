<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HiringMiddleware
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
        // Check if the user is authenticated and has the 'admin' role
        if (Auth::check() && Auth::user()->role === 'hiring_manager') {
            return $next($request);
        }

        // If the user is not an admin, redirect to the login page with an error message
        return redirect()->route('login')->with('error', 'Access denied. Hiring Manager only.');
    }
}
