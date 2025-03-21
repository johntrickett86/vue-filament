<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FilamentRedirectMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check() && auth()->user()->isStaff()) {
            $url = Filament::getUrl();

            return Inertia::location($url);
        }

        return $next($request);
    }
}
