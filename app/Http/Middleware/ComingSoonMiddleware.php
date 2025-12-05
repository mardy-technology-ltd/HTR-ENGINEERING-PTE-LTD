<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComingSoonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if coming soon mode is enabled
        $comingSoonEnabled = env('COMING_SOON_ENABLED', false);
        
        // If coming soon is disabled, proceed normally
        if (!$comingSoonEnabled) {
            return $next($request);
        }
        
        // Check if user has bypass access via session
        if (session()->has('bypass_coming_soon') && session('bypass_coming_soon') === true) {
            return $next($request);
        }
        
        // Exclude secret access route and static assets
        $excludedPaths = [
            'secret-access',
            'clear.php',
            'clear-all-cache-now',
        ];
        
        foreach ($excludedPaths as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }
        
        // Show coming soon page
        return response()->view('coming-soon');
    }
}
