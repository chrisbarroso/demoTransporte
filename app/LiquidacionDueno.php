<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiquidacionDueno extends Model
{

    protected $table = 'liquidacion_dueno';

    protected $fillable = ['id_dueno', 'importe_viajes', 'importe_comision', 'importe_total', 'concepto', 'motivo'];

    protected $hidden = ['id'];

    public function dueno()
    {
        return $this->belongsTo(OwnerUnity::Class, 'id_dueno')->withTrashed();
    }

    public function viajesLiquidados(){
        return $this->hasMany(Waybill::Class, 'id_liquidacion_dueno')
        ->join('units', 'units.id', '=', 'waybills.id_unidad')
        ->select('waybills.*')
        ->orderBy('units.dominio','DESC')
        ->orderBy('waybills.fecha','DESC');
    }

}
