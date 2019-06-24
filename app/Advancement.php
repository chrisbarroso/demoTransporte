<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advancement extends Model
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advancements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_chofer', 'id_unidad', 'id_dueno', 'fecha', 'importe_total', 'liquidado'];

    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['fecha', 'deleted_at'];

    public function driverAde()
    {
        return $this->belongsTo(Driver::Class, 'id_chofer')->withTrashed();
    }

    public function unityAde()
    {
        return $this->belongsTo(Unity::Class, 'id_unidad')->withTrashed();
    }

    public function ownerAde()
    {
        return $this->belongsTo(OwnerUnity::Class, 'id_dueno')->withTrashed();
    }

}
