<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin {
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['admin','superadmin'])) {
        abort(403);
        }
        return $next($request);
    }
}