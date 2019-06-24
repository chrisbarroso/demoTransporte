<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CobranzasClientesFormas extends Model
{

    protected $table = 'cobranzas_clientes_formas';

    protected $fillable = ['id_cobranza_cliente', 'forma', 'importe_efectivo', 'cartera_id'];

    protected $hidden = ['id'];

    public function cartera()
    {
        return $this->belongsTo(Wallet::Class, 'cartera_id');
    }

}
