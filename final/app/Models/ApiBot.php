<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiBot extends Model
{
    protected $table = "turnos";
    protected $fillable = ['fecha','dni','hora'];
    public $timestamps = false;
    
    public function scopeObtenerTurnos($query,$fechaABuscar){
        return $query->selectRaw("TIME_FORMAT(hora, '%H:%i') as horario")->where('fecha','=',$fechaABuscar);
    }
    
    public function scopeExisteTurnoDado($query,$fechaFinal,$horario){
        return $query->where([['fecha','=',$fechaFinal],['hora','=',$horario]]);
    }
}
