<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminAuth Middleware
 * 
 * Ensures that an admin is logged in before allowing access to protected routes
 */
class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is logged in
        if (!session('admin_id')) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        return $next($request);
    }
}
