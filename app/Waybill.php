<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waybill extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'waybills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['idCliente', 'ncporte', 'idLugarPartida', 'idLugarDestino', 'fecha', 'kilometro', 'kilometro', 'tarifa_transporte', 'tarifa_cliente', 'id_mercancia', 'id_chofer', 'id_unidad', 'id_dueno', 'kilo', 'porcentaje', 'importe_transporte', 'importe_porcentaje', 'importe_cliente', 'liquidado_dueno', 'id_liquidacion_dueno', 'liquidado_cliente', 'id_liquidado_cliente'];

    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['fecha', 'deleted_at'];

    public function client()
    {
        return $this->belongsTo(Client::Class, 'idCliente')->withTrashed();
    }

    public function placeDeparture()
    {
        return $this->belongsTo(Place::Class, 'idLugarPartida')->withTrashed();
    }

    public function placeDestination()
    {
        return $this->belongsTo(Place::Class, 'idLugarDestino')->withTrashed();
    }

    public function merca()
    {
        return $this->belongsTo(Mercancia::Class, 'id_mercancia')->withTrashed();
    }

    public function driverCP()
    {
        return $this->belongsTo(Driver::Class, 'id_chofer')->withTrashed();
    }

    public function unityCP()
    {
        return $this->belongsTo(Unity::Class, 'id_unidad')->withTrashed();
    }

    public function ownerCP()
    {
        return $this->belongsTo(OwnerUnity::Class, 'id_dueno')->withTrashed();
    }

}
