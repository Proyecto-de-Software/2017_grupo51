<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    protected $table = 'control_salud';
    protected $fillable = ['fecha','peso','vacunas_completas','vacunas_observaciones','maduracion_acorde','maduracion_observaciones','examen_fisico_normal','examen_fisico_observaciones','percentilo_cefalico','percentilo_perimetro_cefalico','talla','alimentacion','observaciones_generales','id_paciente'];
    public $timestamps = false;
    
    public function paciente(){
        return $this->belongsTo('App\Models\Paciente','id_paciente');
    }
    
    public function usuario(){
        return $this->belongsTo('App\User','id_usuario_registro');
    }
    
    private function consultaGenericaGraficos($query,$campo_devolver,$parametros){
        return $query->select('fecha',$campo_devolver)->where('id_paciente','=',$parametros['id'])->whereBetween('fecha',$parametros['fechas']);
    }
    
    public function scopePeso($query, $parametros){
        return $this->consultaGenericaGraficos($query,'peso',$parametros);
    }
    
    public function scopeTalla($query, $parametros){
        return $this->consultaGenericaGraficos($query,'talla',$parametros);
    }
    
    public function scopePercentiloCefalico($query, $parametros){
        return $this->consultaGenericaGraficos($query,'percentilo_perimetro_cefalico',$parametros);
    }
}
