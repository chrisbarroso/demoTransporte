<?php

namespace App\Http\Controllers;

use App\Waybill;
use App\Client;
use App\Driver;
use App\Place;
use App\Mercancia;
use Illuminate\Http\Request;

class WaybillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keywordNCPorte = $request->searchNCPorte;

        if(isset($keywordNCPorte)) {
        
            $filter = Waybill::where([
                ['ncporte', 'LIKE', "%$keywordNCPorte%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(6);
        }else{
            $filter = Waybill::
            orderBy('id','DESC')
            ->paginate(6);
        }


        return view('waybills', (array) $request->view + [
            'clientes' => Client::get()->sortByDesc('id'),
            'cps' => $filter,
            'cpsEliminados' => Waybill::onlyTrashed()->get()->sortByDesc('id'), 
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $valores = $request->all();
        $cantidadCP = $valores['cantidadCP'];

        return view('waybills.form', [
            'create' => true,
            'CP' => new Waybill(),
            'cantidadCP' => $cantidadCP,
            'choferes' => Driver::get()->sortByDesc('id'),  
            'lugares' => Place::get()->sortByDesc('id'),  
            'mercancias' => Mercancia::get()->sortByDesc('id'), 
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
        
        for ($i = 0; $i < $request->cantidadCP; $i++) {
                
            $request->request->add(['ncporte' => $request['nCartaporte'.$i]]); 

            $validator = \Validator::make($request->all(), [
                'ncporte' => 'required|unique:waybills',
            ]);

            if ($validator->fails()) {
                $AlertType = cookie('AlertType', 'danger', 0.1);
                $Msj = cookie('Msj', 'Numero de carta de porte duplicado', 0.1);
                return redirect()->route('waybills.index')->withCookie($AlertType)->withCookie($Msj);
            }

        }
        
        for ($i = 0; $i < $request->cantidadCP; $i++) {

            $valores = $request->all();
            $waybill = new Waybill($valores);

            $chofer = Driver::findOrFail($request['chofer'.$i]);
     
            $waybill->ncporte = $request['nCartaporte'.$i];
            $waybill->id_chofer = $request['chofer'.$i];
            $waybill->id_unidad = $chofer->unity->id;
            $waybill->id_dueno = $chofer->unity->owner->id;
            $waybill->kilo = $request['kilo'.$i];
            $waybill->porcentaje = $request['porcentaje'.$i];
            $waybill->importe_transporte = $request['importe_transporte'.$i];
            $waybill->importe_porcentaje = $request['importe_porcentaje'.$i];
            $waybill->importe_cliente = $request['importe_cliente'.$i];
            $waybill->liquidado_dueno = 0;
            $waybill->id_liquidacion_dueno = null;
            $waybill->liquidado_cliente = 0;
            $waybill->id_liquidacion_cliente = null;

            $waybill->save();

        }

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Carta/s de porte cargada/s correctamente', 0.1);
        return redirect()->route('waybills.index')->withCookie($AlertType)->withCookie($Msj);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $waybill = Waybill::findOrFail($id);
        return view('waybills.view', [
            'cp' => $waybill,
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
        $waybill = Waybill::findOrFail($id);
        return view('waybills.form', [
            'create' => false,
            'CP' => $waybill,
            'lugares' => Place::get()->sortByDesc('id'),
            'cantidadCP' => 1,
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

        for ($i = 0; $i < $request->cantidadCP; $i++) {
            
            $valores = $request->all();
            $waybill = Waybill::findOrFail($id);
            $waybill->fill($valores);
            $waybill->ncporte = $request['nCartaporte'.$i];
            $waybill->kilo = $request['kilo'.$i];
            $waybill->porcentaje = $request['porcentaje'.$i];
            $waybill->importe_transporte = $request['importe_transporte'.$i];
            $waybill->importe_porcentaje = $request['importe_porcentaje'.$i];
            $waybill->importe_cliente = $request['importe_cliente'.$i];
            
            $waybill->save();
        }

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Carta de porte modificada', 0.1);
        return redirect()->route('waybills.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Waybill::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Carta de porte dada de baja', 0.1);
        return redirect()->route('waybills.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function restore($id){
        Waybill::withTrashed()->where('id', $id)->restore();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Carta de porte recuperada', 0.1);
        return redirect()->route('waybills.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
