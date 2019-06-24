<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unity extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dominio', 'idMarca', 'modelo', 'anio', 'dominioAcoplado', 'idDueno'];

    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function owner()
    {
        return $this->belongsTo(OwnerUnity::Class, 'idDueno');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::Class, 'idMarca');
    }
}
