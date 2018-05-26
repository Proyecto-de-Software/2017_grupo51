<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Configuracion;
use App\Models\Control;
use DateTime;

class ControlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $config = Configuracion::all();
        $controles = Paciente::find($id)->controles()->orderBy('fecha', 'ASC')->paginate($config->toArray()[0]['elementos_pagina']);
        return view('paginaPrincipalControles')->with(['config' => $config, 'controles' => $controles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $config = Configuracion::all();
        return view('formularioControl')->with(['config' => $config,'accion' => 'Crear', 'paciente' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $control = Control::find($id);
        $paciente = $control->paciente;
        $usuarioRealizoControl = $control->usuario;
        $config = Configuracion::all();
        $edad = $this->age($paciente->fecha_nacimiento,$control->fecha);
        if($edad->format('%Y') == 0){
            $edad = $this->weeks($paciente->fecha_nacimiento, $control->fecha);
        }else{
            $edad = $edad->format('%Y').' años';
        }
        return view('detalleControl')->with([
            'control' => $control, 
            'config' => $config, 
            'edad' => $edad, 
            'paciente' => $paciente,
            'usuario' => $usuarioRealizoControl,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $control = Control::find($id);
        $control->delete();
        flash('Se ha eliminado el control.')->important();
        return redirect('controles');
    }
    
    public function weeks($fechaNacimiento,$fechaControl){
        //Calculo de semanas entre 2 fechas
        $datetime1 = new DateTime($fechaNacimiento);
        $datetime2 = new DateTime($fechaControl);
        $interval = $datetime1->diff($datetime2);
        return floor(($interval->format('%a') / 7)) . ' semanas con '
     . ($interval->format('%a') % 7) . ' días';
    }
    
    public function age($fechaNacimiento,$fechaControl){
        //Calculo de años entre 2 fechas
        $fecha_nac = new DateTime(date('Y/m/d',strtotime($fechaNacimiento))); // Creo un objeto DateTime de la fecha ingresada
        $fecha_hoy =  new DateTime(date('Y/m/d',strtotime($fechaControl))); // Creo un objeto DateTime de la fecha de hoy
        $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
        return $edad;
    }
}
