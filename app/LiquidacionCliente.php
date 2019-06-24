<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiquidacionCliente extends Model
{

    protected $table = 'liquidacion_cliente';

    protected $fillable = ['id_cliente', 'importe_viajes', 'importe_total', 'concepto', 'motivo'];

    protected $hidden = ['id'];

    public function cliente()
    {
        return $this->belongsTo(Client::Class, 'id_cliente')->withTrashed();
    }

    public function viajesLiquidados(){
        return $this->hasMany(Waybill::Class, 'id_liquidado_cliente')
        ->orderBy('fecha','DESC');
    }

}
