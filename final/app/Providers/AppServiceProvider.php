<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Paciente;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('mailValidatorEditUser',function($attribute, $value, $parameters, $validator){
            $user = User::find($parameters[0]);
            return empty($user->existeMailIgualAlMio($value)->toArray());
        });
        Validator::extend('customValidDate',function($attribute, $value, $parameters, $validator){
            $today = date("Y-m-d");
            return $parameters[0]<$today;
        });
        Validator::extend('existTelephoneNumber',function($attribute, $value, $parameters, $validator){
            $condition = [['id','!=',$parameters[0]],['tel_cel','=',$parameters[1]]];
            return empty(Paciente::Pacientes($condition)->get()->all());
        });
        Validator::extend('existDoc',function($attribute, $value, $parameters, $validator){
            $condition = [['id','!=',$parameters[0]],['numero_documento','=',$parameters[1]]];
            return empty(Paciente::Pacientes($condition)->get()->all());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
