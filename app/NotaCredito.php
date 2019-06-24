<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaCredito extends Model
{
    use SoftDeletes;

    protected $table = 'notas_credito';

    protected $fillable = ['quien_id', 'quien', 'importe', 'motivo', 'concepto'];

    protected $hidden = ['id'];

    protected $dates = ['deleted_at'];

    public function dueno()
    {
        return $this->belongsTo(OwnerUnity::Class, 'quien_id')->withTrashed();
    }

    public function cliente()
    {
        return $this->belongsTo(Client::Class, 'quien_id')->withTrashed();
    }

    public function proveedor()
    {
        return $this->belongsTo(Provider::Class, 'quien_id')->withTrashed();
    }
}
