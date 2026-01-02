<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Visitor::firstOrCreate([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'date' => now()->toDateString()
        ]);

        return $next($request);
    }
}
