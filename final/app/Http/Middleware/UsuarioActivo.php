<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class UsuarioActivo
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
        $user = Auth::user();
        if(!$user->active){
            $message = 'Usuario temporalmente inactivo.';
            return redirect('logout/'.$message);
        }
        return $next($request);
    }
}
