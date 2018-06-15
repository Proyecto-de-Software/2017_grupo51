<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'apellido' => 'regex:/^[A-Za-zñÑ\s-_]+$/|required',
            'nombre' => 'regex:/^[A-Za-zñÑ\s-_]+$/|required',
            'fecha_nacimiento' => 'date|customValidDate:'.$this->request->all()['fecha_nacimiento'].'|required',
            'genero' => 'regex:/^[A-Za-z\s-_]+$/|required',
            'tipo_documento' => 'regex:/^[A-Za-zñÑ\s-_]+$/|required',
            'numero_documento' => 'numeric|unique:paciente|required',
            'domicilio' => 'string|required',
            'tel_cel' => 'nullable|numeric|unique:paciente',
            'obra_social' => 'regex:/^[A-Za-zñÑ\s-_]+$/',
            'heladera' => 'boolean|required',
            'electricidad' => 'boolean|required',
            'mascota' => 'boolean|required',
            'tipo_vivienda' => 'regex:/^[A-Za-zñÑ\s-_]+$/|required',
            'tipo_calefaccion' => 'regex:/^[A-Za-zñÑ\s-_]+$/|required',
            'tipo_agua' => 'regex:/^[A-Za-zñÑ\s-_]+$/|required',
        ];
    }
    
}
