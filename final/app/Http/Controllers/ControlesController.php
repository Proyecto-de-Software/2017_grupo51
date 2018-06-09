<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Configuracion;
use App\Models\Control;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ControlRequest;
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
        return view('paginaPrincipalControles')->with(['config' => $config, 'controles' => $controles, 'paciente' => $id]);
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
    public function store(ControlRequest $request)
    {
        $control = new Control($request->all());
        $control->fecha = date("Y-m-d");
        $control->id_usuario_registro = Auth::id();
        $control->save();
        flash('Control creado exitosamente.');
        return redirect('controles/'.$control->id.'/show');
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
        $control = Control::find($id);
        $config = Configuracion::all();
        //dd($control);
        return view('formularioControl')->with(['config' => $config,'accion' => 'Editar', 'control' => $control, 'id_usuario' => Auth::id()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ControlRequest $request, $id)
    {
        $control = Control::find($id);
        $control->peso = $request->peso;
        $control->vacunas_completas = $request->vacunas_completas;
        $control->vacunas_observaciones = $request->vacunas_observaciones;
        $control->maduracion_acorde = $request->maduracion_acorde;
        $control->maduracion_observaciones = $request->maduracion_observaciones;
        $control->examen_fisico_normal = $request->examen_fisico_normal;
        $control->examen_fisico_observaciones = $request->examen_fisico_observaciones;
        $control->percentilo_cefalico = $request->percentilo_cefalico;
        $control->percentilo_perimetro_cefalico = $request->percentilo_perimetro_cefalico;
        $control->talla = $request->talla;
        $control->alimentacion = $request->alimentacion;
        $control->observaciones_generales = $request->observaciones_generales;
        $control->save();
        flash('Control modificado exitosamente.');
        return redirect('controles/'.$control->id.'/show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_control, $id_paciente){
        $control = Control::find($id_control);
        $control->delete();
        flash('Se ha eliminado el control.')->important();
        return redirect('controles/'.$id_paciente);
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
