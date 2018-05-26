<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;
use App\Models\Paciente;
use App\Http\Requests\PacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;

class PacientesController extends Controller
{
    
    public function index(){
        $config = Configuracion::all();
        $pacientes = Paciente::Pacientes([])->orderBy('apellido', 'ASC')->paginate($config->toArray()[0]['elementos_pagina']);;
        return view('paginaPrincipalPacientes')->with(['config' => $config, 'pacientes' => $pacientes]);
    }
    
    public function create(){
        $parameters = $this->formViewInfo();
        $parameters += array('accion' => 'Crear');
        return view('formularioPaciente')->with($parameters);
    }
    
    private function formViewInfo(){
        $config = Configuracion::all();
        $documentos_api = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento"));
        $obraSocial_api = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/obra-social"));
        $tipoVivienda_api = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda"));
        $tipoAgua_api = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua"));
        $tipoCalefaccion_api = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion"));
        return [
            'config' => $config,
            'documentos' => $documentos_api,
            'obraSocial' => $obraSocial_api,
            'viviendas' => $tipoVivienda_api,
            'agua' => $tipoAgua_api,
            'calefaccion' => $tipoCalefaccion_api,
            ];
    }
    
    public function store(PacienteRequest $request){
        $paciente = new Paciente($request->all());
        $paciente->save();
        return redirect('pacientes/'.$paciente->id);
    }
    
    public function edit($id){
        $paciente = Paciente::find($id);
        $parameters = $this->formViewInfo();
        $parameters += array('paciente' => $paciente,'accion' => 'Editar');
        return view('formularioPaciente')->with($parameters);
    }
    
    public function show($id){
        $paciente = Paciente::find($id);
        $config = Configuracion::all();
        return view('detallePaciente')->with(['paciente' => $paciente, 'config' => $config]);
    }
    
    public function destroy($id){
        $paciente = Paciente::find($id);
        $paciente->delete();
        flash('Se ha eliminado el paciente.')->important();
        return redirect('pacientes');
    }
    
    public function update(UpdatePacienteRequest $request) {
        $paciente = Paciente::find($request->id);
        $paciente->apellido = $request->apellido;
        $paciente->nombre = $request->nombre;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->genero = $request->genero;
        $paciente->tipo_documento = $request->tipo_documento;
        $paciente->numero_documento = $request->numero_documento;
        $paciente->domicilio = $request->domicilio;
        if(isset($request->tel_cel)){
            $paciente->tel_cel = $request->tel_cel;
        }else{
            $paciente->tel_cel = NULL;
        }
        if($request->obra_social == 'No posee'){
            $paciente->obra_social = NULL;
        }else{
            $paciente->obra_social = $request->obra_social;
        }
        $paciente->heladera = $request->heladera;
        $paciente->electricidad = $request->electricidad;
        $paciente->tipo_vivienda = $request->tipo_vivienda;
        $paciente->mascota = $request->mascota;
        $paciente->tipo_calefaccion = $request->tipo_calefaccion;
        $paciente->tipo_agua = $request->tipo_agua;
        $paciente->save();
        flash('La información del paciente se ha modificado exitosamente.')->info();
        return $this->show($request->id);
    }
}
