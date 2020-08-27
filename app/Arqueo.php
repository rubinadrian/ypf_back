<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arqueo extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['id','cierre_id','1000','500','200','100','50','20','10','5','2','1',
						'cheques','otros_texto','otros_valor','tarjetas','tickets'];
}
