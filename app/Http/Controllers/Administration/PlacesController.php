<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Place;

class PlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administration.places', [
            'lugares' => Place::orderBy('id','DESC')->paginate(6),
            'lugaresEliminados' => Place::onlyTrashed()->get()->sortByDesc('id'),
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.places.form', [
            'create' => true,
            'lugar' => new Place(),
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

        $place = new Place($valores);
        $place->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Lugar cargado correctamente', 0.1);
        return redirect()->route('places.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = Place::findOrFail($id);
        return view('administration.places.view', [
            'lugar' => $place,
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
        $place = Place::findOrFail($id);
        return view('administration.places.form', [
            'create' => false,
            'lugar' => $place,
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

        $place = Place::findOrFail($id);
        $place->fill($valores);
        $place->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Lugar modificado', 0.1);
        return redirect()->route('places.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Place::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Lugar dado de baja', 0.1);
        return redirect()->route('places.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function restore($id){
        Place::withTrashed()->where('id', $id)->restore();
        
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Lugar recuperado', 0.1);
        return redirect()->route('places.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
