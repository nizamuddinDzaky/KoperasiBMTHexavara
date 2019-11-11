<?php

namespace App\Http\Middleware;

use Closure;

class PermissionsRequiredMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $tipe)
    {
        if ($request->user()->tipe != $tipe) {
            return redirect('login');
        } else
            return $next($request);
    }
}
