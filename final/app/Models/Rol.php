<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    
    protected $table = "rol";
    protected $fillable = ['nombre'];
    public $timestamps = false;
    
    public function usuarios(){
        return $this->belongsToMany('App\User','usuario_tiene_rol','rol_id','usuario_id');
    }
    
    public function permisos(){
        return $this->belongsToMany('App\Models\Permiso','rol_tiene_permiso','rol_id','permiso_id');
    }
    
    public function poseePermiso($permiso){
        $poseePermiso = false;
        $permisos = $this->permisos->toArray();
        
        foreach ($permisos as $perm){
            if($perm['nombre'] == $permiso){
                $poseePermiso = true;
                break;
            }
        }
        
        return $poseePermiso;
    }
}
