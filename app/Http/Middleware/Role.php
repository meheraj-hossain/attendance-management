<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        foreach ($roles as $role) {
            // Check if user has the role. This check will depend on how your roles are set up
            if (auth()->user()->role == $role) {
                return $next($request);
            }
        }

        return redirect()->route('unauthorized');
    }
}
