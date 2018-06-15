<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'email|unique:usuario|required',
            'username' => 'max:255|string|required',
            'password' => 'max:255|string|required',
            'first_name' => 'max:255|regex:/^[A-Za-zñÑ\s-_]+$/|required',
            'last_name' => 'max:255|regex:/^[A-Za-zñÑ\s-_]+$/|required',
        ];
    }
}
