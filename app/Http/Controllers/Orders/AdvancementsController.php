<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Advancement;
use App\AdvancementOut;
use App\Driver;
use App\Wallet;

class AdvancementsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('orders.advancements', [
            'adelantos' =>  Advancement::orderBy('id','DESC')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('orders.advancements.form', [
            'create' => true,
            'adelanto' => new Advancement(),
            'choferes' => Driver::get()->sortByDesc('id'),
            'carteras' => Wallet::get()->sortByDesc('id'),
        ]);
    }

    public function store(Request $request)
    {
        $chofer = Driver::findOrFail($request->id_chofer);
        $valores = $request->all();

        $advancement = new Advancement($valores);
        $advancement->id_unidad = $chofer->unity->id;
        $advancement->id_dueno = $chofer->unity->owner->id;
        $advancement->importe_total = 0;
        $advancement->save();

        $importe_total = 0;

        for($i = 1; $i <= $request->totalFormasPagos; $i++) {
            $forma_pago = "forma_pago".$i;

            $AdvancementOut = new AdvancementOut;
            $AdvancementOut->forma_pago = $request->$forma_pago;
            $AdvancementOut->id_sobre_concepto = $advancement->id;

            if($request->$forma_pago == "Efectivo"){
                $importe_efectivo = "importe_efectivo".$i;

                $AdvancementOut->importe_efectivo = $request->$importe_efectivo;
                $AdvancementOut->id_cartera = null;
                $AdvancementOut->id_chequera = null;
                $importe_total = $importe_total + $request->$importe_efectivo; 
            }
            $AdvancementOut->save();
        }

        $advancement_importe_total = Advancement::findOrFail($advancement->id);
        $advancement_importe_total->importe_total = $importe_total;
        $advancement_importe_total->save();
  
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Adelanto cargado correctamente', 0.1);
        return redirect()->route('advancements.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function show($id)
    {
        $advancement = Advancement::findOrFail($id);
        $AdvancementOut = AdvancementOut::where('id_sobre_concepto', $id)->get();
        return view('Orders.advancements.view', [
            'adelanto' => $advancement,
            'pagosSalida' => $AdvancementOut,
        ]);
    }

    public function edit($id)
    {
        $advancement = Advancement::findOrFail($id);
        $AdvancementOut = AdvancementOut::where('id_sobre_concepto', $id)->get();

        if($advancement->liquidado) {
            return view('Orders.advancements.view', [
                'adelanto' => $advancement,
            ]);
        }
        return view('Orders.advancements.form', [
            'create' => false,
            'adelanto' => $advancement,
            'pagosSalida' => $AdvancementOut,
            'choferes' => Driver::get()->sortByDesc('id'),
            'carteras' => Wallet::get()->sortByDesc('id'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $valores = $request->all();

        $advancement = Advancement::findOrFail($id);
        $advancement->fill($valores);
        $advancement->save();

        if($request->totalFormasPagos >= 1) {

            $importe_total = 0;

            for($i = 1; $i <= $request->totalFormasPagos; $i++) {
                $forma_pago = "forma_pago".$i;

                $AdvancementOut = new AdvancementOut;
                $AdvancementOut->forma_pago = $request->$forma_pago;
                $AdvancementOut->id_sobre_concepto = $advancement->id;

                if($request->$forma_pago == "Efectivo"){
                    $importe_efectivo = "importe_efectivo".$i;

                    $AdvancementOut->importe_efectivo = $request->$importe_efectivo;
                    $AdvancementOut->id_cartera = null;
                    $AdvancementOut->id_chequera = null;
                    $importe_total = $importe_total + $request->$importe_efectivo; 
                }
                $AdvancementOut->save();
            }

            $advancement_importe_total = Advancement::findOrFail($advancement->id);
            $advancement_importe_total->importe_total = $importe_total + $advancement_importe_total->importe_total;
            $advancement_importe_total->save();
        }
        
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Adelanto modificado', 0.1);
        return redirect()->route('advancements.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Advancement::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Adelanto dado de baja', 0.1);
        return redirect()->route('advancements.index')->withCookie($AlertType)->withCookie($Msj);
    }


    
}
