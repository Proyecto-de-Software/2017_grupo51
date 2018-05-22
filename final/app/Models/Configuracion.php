<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';
    protected $fillable = ['titulo_pagina','mail_contacto','elementos_pagina','pagina_activa'];
    public $timestamps = false;
    
}
