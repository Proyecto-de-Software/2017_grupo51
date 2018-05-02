<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => 'email|exists:usuario,email|required',Rule::exists('usuario')->where(function ($query){
                    $query->where([
                        ['email','=',$this->email],
                        ['id','!=',$this->id]
                    ]);
                }),
            'username' => 'max:255|string|required',
            'password' => 'max:255|string|required',
            'first_name' => 'max:255|regex:/^[A-Za-z\s-_]+$/|required',
            'last_name' => 'max:255|regex:/^[A-Za-z\s-_]+$/|required',
        ];
    }
}
