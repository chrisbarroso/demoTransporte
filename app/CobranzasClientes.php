<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CobranzasClientes extends Model
{
    use SoftDeletes;

    protected $table = 'cobranzas_clientes';

    protected $fillable = ['cliente_id', 'total', 'motivo', 'concepto', 'motivo_baja'];

    protected $hidden = ['id'];

    protected $dates = ['deleted_at'];

    public function cliente()
    {
        return $this->belongsTo(Client::Class, 'cliente_id')->withTrashed();
    }

    public function cobranzasFormas(){
        return $this->hasMany(CobranzasClientesFormas::Class, 'id_cobranza_cliente')
        ->orderBy('id','DESC');
    }

}
