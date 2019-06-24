<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banco extends Model
{
    protected $table = 'bancos';

    protected $fillable = ['nombre'];

    protected $hidden = ['id'];

}
