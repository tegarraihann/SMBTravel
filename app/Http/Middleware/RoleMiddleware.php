<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is active
        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Check if user has required role
        if (!empty($roles)) {
            $hasRequiredRole = false;
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $hasRequiredRole = true;
                    break;
                }
            }

            if (!$hasRequiredRole) {
                abort(403, 'Unauthorized - Anda tidak memiliki akses untuk halaman ini.');
            }
        }

        return $next($request);
    }
}
