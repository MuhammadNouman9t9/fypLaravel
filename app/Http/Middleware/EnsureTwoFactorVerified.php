<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user has 2FA enabled but not verified in this session
        if ($user && $user->hasTwoFactorEnabled() && ! $request->session()->get('two_factor_verified')) {
            // Allow access to 2FA verification page and logout
            if (! $request->routeIs('two-factor.verify', 'two-factor.show', 'logout')) {
                return redirect()->route('two-factor.verify');
            }
        }

        return $next($request);
    }
}
