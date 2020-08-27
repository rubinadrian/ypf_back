<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aforador extends Model
{
    protected  $table = 'aforadores';
    use SoftDeletes;
    public $timestamps = true;
}
