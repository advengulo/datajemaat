<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Support pipe-separated permissions for backward compatibility
        if (count($permissions) === 1 && str_contains($permissions[0], '|')) {
            $permissions = explode('|', $permissions[0]);
        }

        // Check if user has any of the required permissions
        if ($user->hasAnyPermission($permissions)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. You do not have the required permission to access this resource.');
    }
}
