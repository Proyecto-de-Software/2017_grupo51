<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;
use App\Models\Paciente;

class PacientesController extends Controller
{
    
    public function index(){
        $config = Configuracion::all();
        $pacientes = Paciente::Pacientes([])->orderBy('apellido', 'ASC')->paginate($config->toArray()[0]['elementos_pagina']);;
        return view('paginaPrincipalPacientes')->with(['config' => $config, 'pacientes' => $pacientes]);
    }
}
