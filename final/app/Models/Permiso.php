<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
   protected $table = "permiso";
   protected $fillable = ['nombre'];
   public $timestamps = false;
   
   public function roles(){
        return $this->belongsToMany('App\Models\Rol','rol_tiene_permiso','permiso_id','rol_id');
    }
}
