<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
        // List of roles that are allowed access
        $allowedRoles = ['super_admin', 'hiring_manager', 'recruiter'];

        // Check if the user is authenticated and has one of the allowed roles
        if (Auth::check() && in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request);
        }

        // If the user is not authorized, redirect to the login page with an error message
        return redirect()->route('login')->with('error', 'Access denied. You do not have the required permissions.');
    }
}
