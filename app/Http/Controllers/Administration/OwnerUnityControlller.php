<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OwnerUnity;

class OwnerUnityControlller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administration.owners', [
            'duenos' => OwnerUnity::orderBy('id','DESC')->paginate(6),
            'duenosEliminados' => OwnerUnity::onlyTrashed()->get()->sortByDesc('id'),
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.owners.form', [
            'create' => true,
            'dueno' => new OwnerUnity(),
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
            'cuit' => 'required|string|max:13|unique:owners_units',
        ]);

        if ($validator->fails()) {
            $AlertType = cookie('AlertType', 'danger', 0.1);
            $Msj = cookie('Msj', 'Este cuit ya se encuentra cargado', 0.1);
            return redirect()->route('owners.create')->withCookie($AlertType)->withCookie($Msj);
        } else{
            $owner = new OwnerUnity($valores);
            $owner->liquidacion_pendiente = 0;
            $owner->save();
           
            $AlertType = cookie('AlertType', 'success', 0.1);
            $Msj = cookie('Msj', 'Dueño cargado correctamente', 0.1);
            return redirect()->route('owners.create')->withCookie($AlertType)->withCookie($Msj);
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
        $owner = OwnerUnity::findOrFail($id);
        return view('administration.owners.view', [
            'dueno' => $owner,
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
        $owner = OwnerUnity::findOrFail($id);
        return view('administration.owners.form', [
            'create' => false,
            'dueno' => $owner,
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

        $unity = OwnerUnity::findOrFail($id);
        $unity->fill($valores);
        $unity->save();
       
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Dueño modificado', 0.1);
        return redirect()->route('owners.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OwnerUnity::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Dueño dada de baja', 0.1);
        return redirect()->route('owners.index')->withCookie($AlertType)->withCookie($Msj);

    }

    public function restore($id){
        OwnerUnity::withTrashed()->where('id', $id)->restore();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Dueño recuperado', 0.1);
        return redirect()->route('owners.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
