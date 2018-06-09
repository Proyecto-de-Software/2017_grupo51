<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConfiguracionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Configuracion;

class ConfiguracionController extends Controller
{
    public function index(){
        $config = Configuracion::all();
        return view('paginaPrincialConfiguracion')->with(['config' => $config]);
    }
    
    public function edit(){
        $config = Configuracion::all();
        return view('formularioConfiguracion')->with(['config' => $config]);
    }
    
    public function update(ConfiguracionRequest $request){
        $parametros = $request->all();
        $config = Configuracion::find($parametros['id']);
        $config->titulo_pagina = $parametros['titulo_pagina'];
        $config->mail_contacto = $parametros['mail_contacto'];
        $config->elementos_pagina = $parametros['elementos_pagina'];
        $config->pagina_activa = $parametros['pagina_activa'];
        $config->save();
        flash('La configuración del sitio se ha modificado exitosamente.')->info();
        return $this->index();
    }
}
