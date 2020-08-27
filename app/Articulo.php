<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Articulo extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public static function getArtRelacionMultiple() {
        return DB::connection('oracle')
            ->table('FAMSTOCK')
            ->selectRaw('FAMSTOCK.articulo, FAMSTOCK.denominacion, PROREMUL.articulomadre, PROREMUL.cantidad')
            ->join('PROREMUL', 'PROREMUL.articulohijo', '=', 'FAMSTOCK.articulo')
            ->whereRaw('FAMSTOCK.SECCIONFIS = \'4\'')
            ->whereRaw('FAMSTOCK.BAJA = 0')
            ->whereRaw('PROREMUL.BAJA = 0')
            ->get();
    }

}
