<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagosDuenos extends Model
{
    use SoftDeletes;

    protected $table = 'pagos_duenos';

    protected $fillable = ['dueno_id', 'total', 'motivo', 'concepto', 'motivo_baja'];

    protected $hidden = ['id'];

    protected $dates = ['deleted_at'];

    public function dueno()
    {
        return $this->belongsTo(OwnerUnity::Class, 'dueno_id')->withTrashed();
    }

    public function pagosFormas(){
        return $this->hasMany(PagosDuenosFormas::Class, 'id_pago_dueno')
        ->orderBy('id','DESC');
    }

}
