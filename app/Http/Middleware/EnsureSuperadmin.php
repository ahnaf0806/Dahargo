<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin {
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'superadmin') abort(403);
        return $next($request);
    }
}
