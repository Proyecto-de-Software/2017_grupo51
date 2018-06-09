<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
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
            'titulo_pagina' => 'string|required',
            'mail_contacto' => 'email|required',
            'elementos_pagina' => 'numeric|required',
            'pagina_activa' => 'boolean|required'
        ];
    }
}
