<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Content-Type-Options: Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options: Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-XSS-Protection: Enable XSS filter (legacy but still useful)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: Control browser features
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Strict-Transport-Security: Force HTTPS (only in production)
        if (config('app.env') === 'production' && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content-Security-Policy: Prevent XSS attacks
        // Disable CSP in development to avoid blocking Vite and CDN resources
        if (config('app.env') !== 'local' && config('app.env') !== 'development') {
            // Production: Strict CSP
            $csp = "default-src 'self'; ".
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://fonts.bunny.net; ".
                   "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net; ".
                   "font-src 'self' https://fonts.bunny.net data:; ".
                   "img-src 'self' data: https:; ".
                   "connect-src 'self' https://api.stripe.com; ".
                   'frame-src https://js.stripe.com; '.
                   "object-src 'none'; ".
                   "base-uri 'self'; ".
                   "form-action 'self'; ".
                   "frame-ancestors 'none';";
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Remove server information
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
