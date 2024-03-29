<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $fillable = ['id','email','username','password','active','updated_at','created_at','first_name','last_name'];
    protected $hidden = ['password'];
    
    public function roles(){
        return $this->belongsToMany("App\Models\Rol",'usuario_tiene_rol','usuario_id','rol_id');
    }
    
    public function controles(){
        return $this->hasMany("App\Models\Control");
    }
    
    public function existeMailIgualAlMio($mail){
        return $this->where([
            ['email', '=' , $mail ],
            ['id', '!=' , $this->id ]
        ])->get();
    }
    
    public function scopeUsuarios($query, $value){
        return $query->where($value);
    }
}
