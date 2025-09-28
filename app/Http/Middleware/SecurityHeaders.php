<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Vite;

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
        if (app()->environment('local')) {
            return $next($request);
        }

        // strict transport security
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload',
            $replace = true
        );

        // content security policy
        $response->headers->set(
            'Content-Security-Policy',
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; object-src 'none'; base-uri 'none';",
            $replace = true
        );

        // x frame options
        $response->headers->set(
            'X-Frame-Options',
            'SAMEORIGIN',
            $replace = true
        );

        // x content type options
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff',
            $replace = true
        );

        // refeerer policy
        $response->headers->set(
            'Referrer-Policy',
            'same-origin',
            $replace = true
        );

        // permissions policy

        // removing any other existing policies
        $response->headers->remove('Permissions-Policy');
        $response->headers->remove('Permission-Policy');

        // adding new policies
        $response->headers->set(
            'Permissions-Policy',
            'autoplay=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()',
            $replace = true
        );

        return $response;
    }
}
