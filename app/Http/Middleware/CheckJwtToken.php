<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class CheckJwtToken
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Attempt to authenticate the user using the token from the cookie
            $token = $request->cookie('jwt_token');
            JWTAuth::setToken($token);
            if (!JWTAuth::authenticate()) {
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        return $next($request);
    }
}
