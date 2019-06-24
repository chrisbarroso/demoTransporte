<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mercancia extends Model
{
    
    protected $table = 'mercancias';

    protected $fillable = ['nombre_merca'];

    protected $hidden = ['id'];

}
