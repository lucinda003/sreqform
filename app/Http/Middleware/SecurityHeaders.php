<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $isLocal = app()->environment('local');
        $connectSrc = $isLocal ? "connect-src 'self' ws: wss: http: https:;" : "connect-src 'self';";

        $csp = implode(' ', [
            "default-src 'self';",
            "base-uri 'self';",
            "form-action 'self';",
            "frame-ancestors 'self';",
            "object-src 'none';",
            "img-src 'self' data: blob:;",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval';",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;",
            "style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com;",
            "font-src 'self' data: https://fonts.gstatic.com;",
            $connectSrc,
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        if (! $response->headers->has('X-Content-Type-Options')) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }

        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        if ($request->isSecure() && ! $isLocal) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
