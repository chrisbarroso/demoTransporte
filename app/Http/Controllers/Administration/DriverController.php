<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Controllers\Input;
use App\Driver;
use App\Unity;

class DriverController extends Controller
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
        $keywordNombre = $request->searchNombre;
        $keywordApellido = $request->searchApellido;

        if(isset($keywordNombre) || isset($keywordApellido)) {
            $filter = Driver::where([
                ['nombre', 'LIKE', "%$keywordNombre%"],
                ['apellido', 'LIKE', "%$keywordApellido%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(6);
        }else{
            $filter = Driver::
            orderBy('id','DESC')
            ->paginate(6);
        }

        return view('administration.drivers', (array) $request->view + [
            'choferes' => $filter,
            'choferesEliminados' => Driver::onlyTrashed()->get()->sortByDesc('id'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.drivers.form', [
            'create' => true,
            'chofer' => new Driver(),
            'unidades' => Unity::get()->sortByDesc('id'),
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
            'cuit' => 'required|string|max:13|unique:drivers',
        ]);
    
        if ($validator->fails()) {
            $AlertType = cookie('AlertType', 'danger', 0.1);
            $Msj = cookie('Msj', 'Este cuit ya se encuentra cargado', 0.1);
            return redirect()->route('drivers.create')->withCookie($AlertType)->withCookie($Msj);
        } else {
            $driver = new Driver($valores);
            $driver->save();

            //Actualizamos unidad usada
            $idUnidad = $request->idUnidad;

            $unity = Unity::findOrFail($idUnidad);
            $unity->usado = true;
            $unity->save();

            $AlertType = cookie('AlertType', 'success', 0.1);
            $Msj = cookie('Msj', 'Chofer cargado correctamente', 0.1);
            return redirect()->route('drivers.index')->withCookie($AlertType)->withCookie($Msj);
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
        $driver = Driver::findOrFail($id);
        return view('administration.drivers.view', [
            'chofer' => $driver,
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
        $driver = Driver::findOrFail($id);
        return view('administration.drivers.form', [
            'create' => false,
            'chofer' => $driver,
            'unidades' => Unity::get()->sortByDesc('id'),
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
        //Tomo todos los valores del formulario
        $valores = $request->all();

        //Primero pregunto si es necesario actualizar la unidad
        if ($valores['idUnidadUsada'] != $valores['idUnidad']) {
            
            //Actualizo la unidad que se deja de usar
            if ($valores['idUnidadUsada'] != null) {
                $unityUsada = Unity::findOrFail($valores['idUnidadUsada']);
                $unityUsada->usado = false;
                $unityUsada->save();
            }
            
            //Actualizo la unidad que se usa
            if ($valores['idUnidad'] != null) {
                $unity = Unity::findOrFail($valores['idUnidad']);
                $unity->usado = true;
                $unity->save();
            }     
        }

        unset($valores['idUnidadUsada']);
        unset($valores['cuit']);
        $driver = Driver::findOrFail($id);
        $driver->fill($valores);
        $driver->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Chofer modificado', 0.1);
        return redirect()->route('drivers.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);

        //Primero me fijo si tiene unidad para liberar
        if ($driver->idUnidad != null) {
            //Primero pongo usado en 0 en Unity
            $unity = Unity::findOrFail($driver->idUnidad);
            $unity->usado = false;
            $unity->save();

            //Segundo se actualiza el idUnidad en el drivers = null
            $driver->idUnidad = null;
            $driver->save();
        }
        Driver::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Chofer dado de baja', 0.1);
        return redirect()->route('drivers.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function restore($id){
        Driver::withTrashed()->where('id', $id)->restore();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Chofer recuperado', 0.1);
        return redirect()->route('drivers.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
