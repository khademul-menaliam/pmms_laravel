<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictImpersonationEdits
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('impersonated_user_id')) {
            $impersonatedUserId = $request->session()->get('impersonated_user_id');

            // 1. Swap user for the rest of the request
            // This ensures auth()->user(), $request->user(), and scopes like ownedBy() use the target user.
            $impersonatedUser = \App\Models\User::find($impersonatedUserId);
            if ($impersonatedUser) {
                auth()->setUser($impersonatedUser);
                $request->setUserResolver(fn () => $impersonatedUser);
            }

            // 2. Restrict non-GET actions except for stopping impersonation
            if (! $request->isMethod('GET') &&
                ! $request->isMethod('HEAD') &&
                ! $request->routeIs('admin.stop-impersonating')) {
                return back()->with('error', 'Actions are restricted in "View As" mode.');
            }
        }

        return $next($request);
    }
}
