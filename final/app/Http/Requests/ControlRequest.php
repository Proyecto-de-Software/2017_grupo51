<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ControlRequest extends FormRequest
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
            'peso' => 'numeric|required',
            'vacunas_completas' => 'boolean|required',
            'vacunas_observaciones' => 'string|required',
            'maduracion_acorde' => 'boolean|required',
            'maduracion_observaciones' => 'string|required',
            'examen_fisico_normal' => 'boolean|required',
            'examen_fisico_observaciones' => 'string|required',
            'percentilo_cefalico' => 'nullable|numeric',
            'percentilo_perimetro_cefalico' => 'nullable|numeric',
            'talla' => 'nullable|numeric',
            'alimentacion' => 'nullable|string',
            'observaciones_generales' => 'nullable|string',
        ];
    }
}
