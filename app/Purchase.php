<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_chofer', 'id_unidad', 'id_dueno', 'id_proveedor', 'fecha', 'nro_control', 'tanque_lleno', 'litros', 'importe', 'confirmado', 'liquidado'];

    protected $hidden = ['id', 'id_chofer', 'id_unidad', 'id_dueno', 'id_proveedor'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['fecha', 'deleted_at'];

    public function driverPur()
    {
        return $this->belongsTo(Driver::Class, 'id_chofer')->withTrashed();
    }

    public function unityPur()
    {
        return $this->belongsTo(Unity::Class, 'id_unidad')->withTrashed();
    }

    public function ownerPur()
    {
        return $this->belongsTo(OwnerUnity::Class, 'id_dueno')->withTrashed();
    }

    public function providerPur()
    {
        return $this->belongsTo(Provider::Class, 'id_proveedor')->withTrashed();
    }
}
