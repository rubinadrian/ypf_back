<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playero extends Model
{
    use SoftDeletes;
    public $timestamps = true;
}
