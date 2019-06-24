<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Purchase;
use App\Driver;
use App\Provider;

class PurchasesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('orders.purchases', (array) $request->view + [
            'compras' =>  Purchase::orderBy('id','DESC')->paginate(6),
            'comprasEliminadas' => Purchase::onlyTrashed()->get()->sortByDesc('id'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('orders.purchases.form', [
            'create' => true,
            'compra' => new Purchase(),
            'choferes' => Driver::get()->sortByDesc('id'),
            'proveedores' => Provider::get()->sortByDesc('id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chofer = Driver::findOrFail($request->id_chofer);

        $valores = $request->all();

        $purchase = new Purchase($valores);
        $purchase->id_unidad = $chofer->unity->id;
        $purchase->id_dueno = $chofer->unity->owner->id;
        $purchase->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Orden de compra cargado correctamente', 0.1);
        return redirect()->route('purchases.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('Orders.purchases.view', [
            'compra' => $purchase,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        if($purchase->liquidado) {
            return view('Orders.purchases.view', [
                'compra' => $purchase,
            ]);
        }
        return view('Orders.purchases.form', [
            'create' => false,
            'compra' => $purchase,
            'choferes' => Driver::get()->sortByDesc('id'),
            'proveedores' => Provider::get()->sortByDesc('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valores = $request->all();

        $purchase = Purchase::findOrFail($id);
        $purchase->fill($valores);
        if(!$request->tanque_lleno) {
            $purchase->tanque_lleno = 0;
        }
        $purchase->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Orden de compra modificada', 0.1);
        return redirect()->route('purchases.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function confirmations(Request $request, $id) {
        $valores = $request->all();

        $purchase = Purchase::findOrFail($id);
        $purchase->fill($valores);
        $purchase->confirmado = 1;
        $purchase->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Orden de compra confirmada', 0.1);
        return redirect()->route('purchases.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Purchase::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Orden de compra dada de baja', 0.1);
        return redirect()->route('purchases.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function restore($id) {
        Purchase::withTrashed()->where('id', $id)->restore();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Orden de compra recuperada', 0.1);
        return redirect()->route('purchases.index')->withCookie($AlertType)->withCookie($Msj);
    }
    
}
