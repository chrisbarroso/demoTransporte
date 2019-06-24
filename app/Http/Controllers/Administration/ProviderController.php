<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Controllers\Input;
use App\Provider;

class ProviderController extends Controller
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
        return view('administration.providers', [
            'proveedores' => Provider::orderBy('id','DESC')->paginate(6),
            'proveedoresEliminados' => Provider::onlyTrashed()->get()->sortByDesc('id'),
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.providers.form', [
            'create' => true,
            'proveedor' => new Provider(),
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
        $valores = $request->all();

        $validator = \Validator::make($valores, [
            'cuit' => 'required|string|max:13|unique:providers',
        ]);
    
        if ($validator->fails()) {
            $AlertType = cookie('AlertType', 'danger', 0.1);
            $Msj = cookie('Msj', 'Este cuit ya se encuentra cargado', 0.1);
            return redirect()->route('providers.create')->withCookie($AlertType)->withCookie($Msj);
        }else {
            $provider = new Provider($valores);
            $provider->save();

            $AlertType = cookie('AlertType', 'success', 0.1);
            $Msj = cookie('Msj', 'Proveedor cargado correctamente', 0.1);
            return redirect()->route('providers.index')->withCookie($AlertType)->withCookie($Msj);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = Provider::findOrFail($id);
        return view('administration.providers.view', [
            'proveedor' => $provider,
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
        $provider = Provider::findOrFail($id);
        return view('administration.providers.form', [
            'create' => false,
            'proveedor' => $provider,
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
        unset($valores['cuit']);
    
        $provider = Provider::findOrFail($id);
        $provider->fill($valores);
        $provider->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Proveedor modificado', 0.1);
        return redirect()->route('providers.index')->withCookie($AlertType)->withCookie($Msj);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Provider::destroy($id);
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Proveedor dado de baja', 0.1);
        return redirect()->route('providers.index')->withCookie($AlertType)->withCookie($Msj);
    }
    
    public function restore($id){
        Provider::withTrashed()->where('id', $id)->restore();
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Proveedor recuperado', 0.1);
        return redirect()->route('providers.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
