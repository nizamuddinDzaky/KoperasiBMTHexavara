<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionsUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->status != 2){
            if(Auth::user()->tipe=="anggota")return redirect('/anggota/datadiri');
            else redirect('/teller/datadiri');
        }
        return $next($request);
    }
}
