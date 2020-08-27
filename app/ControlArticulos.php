<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlArticulos extends Model
{
    //use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['cierre_id', 'articulo_id', 'yer', 'inicial', 'reposicion', 'final'];


    public function Articulos()
    {
        return $this->belongsTo('App\Articulo', 'articulo_id', 'id');
    }
}
