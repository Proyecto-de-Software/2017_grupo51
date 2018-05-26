<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\PacienteRequest;

class UpdatePacienteRequest extends PacienteRequest
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
        $rules = parent::rules();
        if(isset($this->all()['numero_documento'])){
            $rules['numero_documento'] = 'numeric|existDoc:'.$this->all()['id'].','.$this->all()['numero_documento'];
        }else{
            $rules['numero_documento'] = '';
        }
        if(isset($this->all()['tel_cel'])){
            $rules['tel_cel'] = 'numeric|existTelephoneNumber:'.$this->all()['id'].','.$this->all()['tel_cel'];
        }else{
            $rules['tel_cel'] = '';
        }
        return $rules;
    }
}
