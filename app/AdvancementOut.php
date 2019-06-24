<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvancementOut extends Model
{
    
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advancements_out';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['forma_pago', 'id_sobre_concepto', 'importe_efectivo', 'id_cartera', 'id_chequera'];

    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function walletOut()
    {
        return $this->belongsTo(Wallet::Class, 'id_cartera');
    }
}
