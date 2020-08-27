<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    public $timestamps = true;
    protected $fillable = ['articulo', 'denominacion', 'cantidad', 'cierre_id'];
}
