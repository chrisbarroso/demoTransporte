<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerUnity extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'owners_units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'apellido', 'cuit', 'direccion', 'nacimiento', 'CP', 'tel', 'usado'];

    protected $hidden = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['nacimiento', 'deleted_at'];

    public function getNroCuentaCorrienteAttribute()
    {
        return str_pad($this->id, 8, "0", STR_PAD_LEFT);
    }

    public function viajesLiquidadar(){
        return $this->hasMany(Waybill::Class, 'id_dueno')
        ->join('units', 'units.id', '=', 'waybills.id_unidad')
        ->where([
            ['waybills.liquidado_dueno', '=', "0"],
        ])
        ->select('waybills.*')
        ->orderBy('units.dominio','DESC')
        ->orderBy('waybills.fecha','DESC');
    }

    public function cuentaCorrienteLiqui() {
        return $this->hasMany(LiquidacionDueno::Class, 'id_dueno')
        ->select('created_at', 'id', 'concepto', 'motivo', 'importe_total as imp')
        ->orderBy('created_at','ASC');
    }
    
    public function cuentaCorrienteNC() {
        return $this->hasMany(NotaCredito::Class, 'quien_id')
        ->select('created_at', 'id', 'concepto', 'motivo', 'importe as imp')
        ->where([
            ['quien', '=', 'Dueño'],
        ])
        ->orderBy('created_at','ASC');
    }

    public function cuentaCorrienteND() {
        return $this->hasMany(NotaDebito::Class, 'quien_id')
        ->select('created_at', 'id', 'concepto', 'motivo', 'importe as imp')
        ->where([
            ['quien', '=', 'Dueño'],
        ])
        ->orderBy('created_at','ASC');
    }

    /*public function cuentaCorrienteC() {
        return $this->hasMany(CobranzasClientes::Class, 'cliente_id')
        ->select('created_at', 'id', 'concepto', 'motivo', 'total as imp')
        ->orderBy('created_at','ASC');
    }*/

    public function todos($desde, $hasta) {

        $uno = $this->cuentaCorrienteLiqui();
        $dos = $this->cuentaCorrienteNC();
        $tres = $this->cuentaCorrienteND();

        if(($desde == null) and ($hasta == null)) {

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);

            return $total->orderBy('created_at','ASC')->get();
              
        }else if(($desde == null) and ($hasta != null)) {
            $hasta = $hasta." 23:59:59";

            $uno = $uno->where([['created_at', '<=', $hasta]]);
            $dos = $dos->where([['created_at', '<=', $hasta]]);
            $tres = $tres->where([['created_at', '<=', $hasta]]);

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);

            return $total->orderBy('created_at','ASC')->get();

        }else if(($hasta == null) and ($desde != null)) {
            $desde = $desde." 00:00:00";

            $uno = $uno->where([['created_at', '>=', $desde]]);
            $dos = $dos->where([['created_at', '>=', $desde]]);
            $tres = $tres->where([['created_at', '>=', $desde]]);

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);

            return $total->orderBy('created_at','ASC')->get();
    
        }else {
            $desde = $desde." 00:00:00";
            $hasta = $hasta." 23:59:59";

            $uno = $uno->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);
            $dos = $dos->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);
            $tres = $tres->where([['created_at', '>=', $desde], ['created_at', '<=', $hasta]]);

            $total1 = $uno->unionAll($dos);
            $total = $total1->unionAll($tres);

            return $total->orderBy('created_at','ASC')->get();
        }
    }

    /* Consutar saldo total */
    public function total($hastaT) {

        $unoT = $this->cuentaCorrienteLiqui();
        $dosT = $this->cuentaCorrienteNC();
        $tresT = $this->cuentaCorrienteND();
        //$cuatroT = $this->cuentaCorrienteC();

        if($hastaT == null) {
            $TOTAL1 = $unoT->unionAll($dosT);
            $TOTAL2 = $TOTAL1->unionAll($tresT);
            //$TOTAL3 = $TOTAL2->unionAll($cuatroT);

            return $TOTAL2->orderBy('created_at','ASC')->get();
        }else if($hastaT != null) {
            $hastaT = $hastaT." 23:59:59";

            $unoT = $unoT->where([['created_at', '<=', $hastaT]]);
            $dosT = $dosT->where([['created_at', '<=', $hastaT]]);
            $tresT = $tresT->where([['created_at', '<=', $hastaT]]);
            //$cuatroT = $cuatroT->where([['created_at', '<=', $hastaT]]);

            $TOTAL1 = $unoT->unionAll($dosT);
            $TOTAL2 = $TOTAL1->unionAll($tresT);
            //$TOTAL3 = $TOTAL2->unionAll($cuatroT);

            return $TOTAL2->orderBy('created_at','ASC')->get();
        }

    }



}
