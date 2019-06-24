<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['razonsocial', 'tel', 'cuit', 'direccion', 'CP'];

    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function viajesLiquidadar(){
        return $this->hasMany(Waybill::Class, 'idCliente')
        ->where([
            ['liquidado_cliente', '=', "0"],
        ])
        ->orderBy('fecha','DESC');
    }

    public function cuentaCorrienteLiqui() {
        return $this->hasMany(LiquidacionCliente::Class, 'id_cliente')
        ->select('created_at', 'id', 'concepto', 'motivo', 'importe_total as imp')
        ->orderBy('created_at','ASC');
    }
    
    public function cuentaCorrienteNC() {
        return $this->hasMany(NotaCredito::Class, 'quien_id')
        ->select('created_at', 'id', 'concepto', 'motivo', 'importe as imp')
        ->where([
            ['quien', '=', 'Cliente'],
        ])
        ->orderBy('created_at','ASC');
    }

    public function cuentaCorrienteND() {
        return $this->hasMany(NotaDebito::Class, 'quien_id')
        ->select('created_at', 'id', 'concepto', 'motivo', 'importe as imp')
        ->where([
            ['quien', '=', 'Cliente'],
        ])
        ->orderBy('created_at','ASC');
    }

    public function cuentaCorrienteC() {
        return $this->hasMany(CobranzasClientes::Class, 'cliente_id')
        ->select('created_at', 'id', 'concepto', 'motivo', 'total as imp')
        ->orderBy('created_at','ASC');
    }

    public function todos($desde, $hasta) {

        $uno = $this->cuentaCorrienteLiqui();
        $dos = $this->cuentaCorrienteNC();
        $tres = $this->cuentaCorrienteND();
        $cuatro = $this->cuentaCorrienteC();

        if(($desde == null) and ($hasta == null)) {

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);
            $total = $total->unionAll($cuatro);

            return $total->orderBy('created_at','ASC')->get();
              
        }else if(($desde == null) and ($hasta != null)) {
            $hasta = $hasta." 23:59:59";

            $uno = $uno->where([['created_at', '<=', $hasta]]);
            $dos = $dos->where([['created_at', '<=', $hasta]]);
            $tres = $tres->where([['created_at', '<=', $hasta]]);
            $cuatro = $cuatro->where([['created_at', '<=', $hasta]]);

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);
            $total = $total->unionAll($cuatro);

            return $total->orderBy('created_at','ASC')->get();

        }else if(($hasta == null) and ($desde != null)) {
            $desde = $desde." 00:00:00";

            $uno = $uno->where([['created_at', '>=', $desde]]);
            $dos = $dos->where([['created_at', '>=', $desde]]);
            $tres = $tres->where([['created_at', '>=', $desde]]);
            $cuatro = $cuatro->where([['created_at', '>=', $desde]]);

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);
            $total = $total->unionAll($cuatro);

            return $total->orderBy('created_at','ASC')->get();
    
        }else {
            $desde = $desde." 00:00:00";
            $hasta = $hasta." 23:59:59";

            $uno = $uno->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);
            $dos = $dos->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);
            $tres = $tres->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);
            $cuatro = $cuatro->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);
            $total = $total->unionAll($cuatro);

            return $total->orderBy('created_at','ASC')->get();
        }
    }

    /* Consutar saldo total */
    public function total($hastaT) {

        $unoT = $this->cuentaCorrienteLiqui();
        $dosT = $this->cuentaCorrienteNC();
        $tresT = $this->cuentaCorrienteND();
        $cuatroT = $this->cuentaCorrienteC();

        if($hastaT == null) {
            $TOTAL1 = $unoT->unionAll($dosT);
            $TOTAL2 = $TOTAL1->unionAll($tresT);
            $TOTAL3 = $TOTAL2->unionAll($cuatroT);

            return $TOTAL3->orderBy('created_at','ASC')->get();
        }else if($hastaT != null) {
            $hastaT = $hastaT." 23:59:59";

            $unoT = $unoT->where([['created_at', '<=', $hastaT]]);
            $dosT = $dosT->where([['created_at', '<=', $hastaT]]);
            $tresT = $tresT->where([['created_at', '<=', $hastaT]]);
            $cuatroT = $cuatroT->where([['created_at', '<=', $hastaT]]);

            $TOTAL1 = $unoT->unionAll($dosT);
            $TOTAL2 = $TOTAL1->unionAll($tresT);
            $TOTAL3 = $TOTAL2->unionAll($cuatroT);

            return $TOTAL3->orderBy('created_at','ASC')->get();
        }

    }
    

}
