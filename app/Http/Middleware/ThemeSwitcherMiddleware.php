<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThemeSwitcherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle($request, Closure $next): mixed
    {
        if (session()->has('backpack.theme-tabler.layout'))
            config(['backpack.theme-tabler.layout' => session('backpack.theme-tabler.layout')]);

        return $next($request);
    }
}
