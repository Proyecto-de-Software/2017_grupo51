<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Closure;

class Permiso
{
    
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permiso)
    {
        $poseePermiso = false;
        $user = Auth::user();
        $roles = $user->roles;
        foreach ($roles as $rol){
            if($rol->poseePermiso($permiso)){
                $poseePermiso = true;
                break;
            }
        }
        if ($poseePermiso){
            return $next($request);
        }else{
            if($permiso == 'usuario_index'){
                return redirect('/usuarios/'.$user->id);
            }
            flash('No posees permisos para acceder a dicha funcionalidad o seccion.')->error()->important();
            return redirect()->route('home');
        }
        
    }
}
