<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    $user = auth()->user();

    // Validates based on your database columns
    $isAdmin = ($user->is_admin == 1 || 
                $user->role === 'admin' || 
                $user->role === 'sub admin');

    if (!$isAdmin) {
        abort(403, 'ADMIN ONLY');
    }

    return $next($request);
}
}
