<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NotaCredito;
use App\OwnerUnity;
use App\Client;
use App\Provider;

class NotaCreditoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('orders.notascredito', [
            'notas' => NotaCredito::orderBy('id','DESC')->paginate(15),
        ]); 
    }

    public function create()
    {
        return view('Orders.notascredito.form', [
            'NotaCredito' => new NotaCredito(),
            'duenos' => OwnerUnity::get()->sortByDesc('id'),
            'clientes' => Client::get()->sortByDesc('id'),
            'proveedores' => Provider::get()->sortByDesc('id'),
        ]);
    }

    public function store(Request $request)
    {
        if($request->quien == 1) {
            $quien = "Dueño";
        }else if($request->quien == 2) {
            $quien = "Cliente";
        }else {
            $quien = "Proveedor";
        }

        $ncredit = new NotaCredito();
        $ncredit->quien_id = $request->quien_id;
        $ncredit->quien = $quien;
        $ncredit->importe = $request->importe;
        $ncredit->motivo = $request->motivo;
        $ncredit->concepto = "Nota de credito";
        $ncredit->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Nota de credito cargada correctamente', 0.1);
        return redirect()->route('credito.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function destroy($id)
    {
        NotaCredito::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Carta de porte dada de baja', 0.1);
        return redirect()->route('credito.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
