<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access if the user is a superadmin OR if we are currently impersonating.
        // This ensures the admin who initiated the impersonation doesn't get locked out of admin routes.
        if (auth()->check() && (auth()->user()->is_superadmin || $request->session()->has('impersonated_user_id'))) {
            return $next($request);
        }

        return redirect()
            ->route('dashboard')
            ->with('error', 'Unauthorized access.');
    }
}
