<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For API routes called from admin dashboard, we need to check session-based auth
        // This is because the admin is logged in via web session, not API token

        // Start session if not already started
        if (!session()->isStarted()) {
            session()->start();
        }

        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Admin authentication required.',
                'error' => 'Authentication failed'
            ], 401);
        }

        return $next($request);
    }
}
