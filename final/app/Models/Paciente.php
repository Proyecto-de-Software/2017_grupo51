<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = "paciente";
    protected $fillable = ["apellido","nombre","fecha_nacimiento","genero","tipo_documento","numero_documento","domicilio","tel_cel","obra_social","heladera","electricidad","tipo_vivienda","mascota","tipo_calefaccion","tipo_agua"];
    public $timestamps = false;
    
    
    public function scopePacientes($query, $value){
        return $query->where($value);
    }
}
