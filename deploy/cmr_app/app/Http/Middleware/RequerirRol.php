<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequerirRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless(
            $request->user() && in_array($request->user()->rol->value, $roles, true),
            403,
            'No tenés permisos para acceder a esta sección.'
        );

        return $next($request);
    }
}
