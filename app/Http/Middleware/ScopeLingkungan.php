<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScopeLingkungan
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that users with lingkungan_admin role
     * only access data from their assigned lingkungan.
     * Superadmin and SNK roles bypass this restriction.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Skip for superadmin - they have access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Skip for SNK - they have access to all lingkungan
        if ($user->isSnk()) {
            return $next($request);
        }

        // For lingkungan_admin, check if they have at least one lingkungan assigned
        if ($user->isLingkunganAdmin()) {
            $lingkunganIds = $user->getLingkunganIds();

            if ($lingkunganIds->isEmpty()) {
                abort(403, 'Unauthorized. No lingkungan assigned to your account. Please contact administrator.');
            }

            // Store accessible lingkungan IDs in request for use in controllers/models
            $request->merge(['accessible_lingkungan_ids' => $lingkunganIds->toArray()]);

            return $next($request);
        }

        // If user doesn't have any recognized role, deny access
        abort(403, 'Unauthorized. You do not have the required role to access this resource.');
    }
}
