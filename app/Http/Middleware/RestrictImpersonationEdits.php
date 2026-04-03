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
        if ($request->session()->has('impersonated_user_id') &&
            ! $request->isMethod('GET') &&
            ! $request->isMethod('HEAD') &&
            ! $request->routeIs('admin.stop-impersonating')) {
            return back()->with('error', 'Actions are restricted in "View As" mode.');
        }

        return $next($request);
    }
}
