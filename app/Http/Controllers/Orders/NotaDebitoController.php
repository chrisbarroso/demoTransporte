<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NotaDebito;
use App\OwnerUnity;
use App\Client;
use App\Provider;

class NotaDebitoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('orders.notasdebito', [
            'notas' => NotaDebito::orderBy('id','DESC')->paginate(15),
        ]); 
    }

    public function create()
    {
        return view('Orders.notasdebito.form', [
            'NotaDebito' => new NotaDebito(),
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

        $ndebit = new NotaDebito();
        $ndebit->quien_id = $request->quien_id;
        $ndebit->quien = $quien;
        $ndebit->importe = $request->importe;
        $ndebit->motivo = $request->motivo;
        $ndebit->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Nota de debito cargada correctamente', 0.1);
        return redirect()->route('debito.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function destroy($id)
    {
        NotaCredito::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Carta de porte dada de baja', 0.1);
        return redirect()->route('credito.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
