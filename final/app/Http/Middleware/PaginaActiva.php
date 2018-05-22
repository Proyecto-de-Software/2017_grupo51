<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class PaginaActiva
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
    public function handle($request, Closure $next)
    {
        $config = Configuracion::all()->toArray();
        if($config[0]['pagina_activa']){
            return $next($request);
        }else{
            if(Auth::user() == NULL){
                return redirect('/inactive');
            }else{
                $poseePermiso = false;
                $roles = Auth::user()->roles;
                foreach ($roles as $rol){
                    if($rol->poseePermiso('configuracion')){
                        $poseePermiso = true;
                        break;
                    }
                }
                if($poseePermiso){
                    return $next($request);
                }else{
                    return redirect('/logout');
                }
            }
        }       
    }
}
