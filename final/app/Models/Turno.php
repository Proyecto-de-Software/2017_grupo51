<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = 'turnos';
    protected $fillable = ['fecha','dni','hora'];
    public $timestamps = false;
}
