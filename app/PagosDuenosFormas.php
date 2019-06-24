<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagosDuenosFormas extends Model
{

    protected $table = 'pagos_duenos_formas';

    protected $fillable = ['id_pago_dueno', 'forma', 'importe_efectivo', 'cartera_id'];

    protected $hidden = ['id'];

    public function cartera()
    {
        return $this->belongsTo(Wallet::Class, 'cartera_id');
    }

}
