<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Session;
use App\Unity;
use App\OwnerUnity;
use App\Brand;

class UnityController extends Controller
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
        $keywordDominio = $request->searchDominio;

        if(isset($keywordDominio)) {
            $filter = Unity::where([
                ['dominio', 'LIKE', "%$keywordDominio%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(6);
        }else{
            $filter = Unity::
            orderBy('id','DESC')
            ->paginate(6);
        }

        return view('administration.units', (array) $request->view + [
            'unidades' => $filter,
            'unidadesEliminados' => Unity::onlyTrashed()->get()->sortByDesc('id'), 
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
        $redireccion = null;
        if(isset($valores['redireccion'])){
            $redireccion = $valores['redireccion'];
        }

        if($redireccion == "RedirecCreateDriver") {
            Session::put('RedirecCreateDriver', 1);
        }else {
            Session::put('RedirecCreateDriver', 0);
        }

        return view('administration.units.form', [
            'create' => true,
            'unidad' => new Unity(),
            'duenosUnidades' => OwnerUnity::get()->sortByDesc('id'),
            'marcas' => Brand::get()->sortByDesc('id'),
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

        $casos = $valores['casos'];

        switch ($casos) {
            case "unidad":

                $redireccion2 = "";
                if(Session::get('RedirecCreateDriver') == 1){
                    $redireccion2 = "RedirecCreateDriver";
                }

                $validator = \Validator::make($valores, [
                    'dominio' => 'required|string|unique:units',
                ]);
            
                if ($validator->fails()) {
                    //return redirect()->route('units.create', ['redireccion' => $redireccion2, 'AlertType' => 'danger', 'Msj' => 'Dominio ya se encuentra cargado']);

                    $AlertType = cookie('AlertType', 'danger', 0.1);
                    $Msj = cookie('Msj', 'Dominio ya se encuentra cargado', 0.1);
                    return redirect()->route('units.create', ['redireccion' => $redireccion2])->withCookie($AlertType)->withCookie($Msj);
                }else {
                    if(Session::get('RedirecCreateDriver') == 1){
                        $redireccion = "drivers.create";
                    }else {
                        $redireccion = "units.index";
                    }
    
                    unset($valores['redireccion']);

                    //Actualizo que el dueno esta usado
                    $idDueno = $request->idDueno;

                    $owner = OwnerUnity::findOrFail($idDueno);
                    $owner->usado = true;
                    $owner->save();

                    $driver = new Unity($valores);
                    $driver->save();
    
                    $AlertType = cookie('AlertType', 'success', 0.1);
                    $Msj = cookie('Msj', 'Unidad cargado correctamente', 0.1);
                    return redirect()->route($redireccion)->withCookie($AlertType)->withCookie($Msj);
                }
                break;
            case "dueno":
                $redireccion = "";
                if(Session::get('RedirecCreateDriver') == 1){
                    $redireccion = "RedirecCreateDriver";
                }
                $validator = \Validator::make($valores, [
                    'cuit' => 'required|string|max:13|unique:owners_units',
                ]);
            
                if ($validator->fails()) {
                    $AlertType = cookie('AlertType', 'danger', 0.1);
                    $Msj = cookie('Msj', 'Este cuit ya se encuentra cargado', 0.1);
                    return redirect()->route('units.create', ['redireccion' => $redireccion])->withCookie($AlertType)->withCookie($Msj);
                } else{
                    unset($valores['redireccion']);

                    $owner = new OwnerUnity($valores);
                    $owner->liquidacion_pendiente = 0;
                    $owner->save();
                   
                    $AlertType = cookie('AlertType', 'success', 0.1);
                    $Msj = cookie('Msj', 'Dueño cargado correctamente', 0.1);
                    return redirect()->route('units.create', ['redireccion' => $redireccion])->withCookie($AlertType)->withCookie($Msj);
                }
                break;
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
        $unity = Unity::findOrFail($id);
        return view('administration.units.view', [
            'unidad' => $unity,
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
        $unity = Unity::findOrFail($id);
        return view('administration.units.form', [
            'create' => false,
            'unidad' => $unity,
            'duenosUnidades' => OwnerUnity::get()->sortByDesc('id'),
            'marcas' => Brand::get()->sortByDesc('id'),
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

        unset($valores['dominio']);

        //Actualizo que el dueno esta usado
        $idDueno = $request->idDueno;
        if (isset($request->idDuenoAnterior)) {
            $idDuenoAnterior = $request->idDuenoAnterior;
        } else {
            $idDuenoAnterior = $idDueno;
        }

         if ($idDuenoAnterior != $idDueno) {

            $cantidadUsados = Unity::where([
                ['idDueno', '=', $idDuenoAnterior],
            ])->count();

           if ($cantidadUsados <= 1) {
                //Actualizo dueno anterior en 0
                $owner = OwnerUnity::findOrFail($idDuenoAnterior);
                $owner->usado = false;
                $owner->save(); 
            }

         }

        //pongo en true dueno nuevo
        $owner = OwnerUnity::findOrFail($request->idDueno);
        $owner->usado = true;
        $owner->save();
 
         $unity = Unity::findOrFail($id);
         $unity->fill($valores);
         $unity->save();
         
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Unidad modificada', 0.1);
        return redirect()->route('units.index')->withCookie($AlertType)->withCookie($Msj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Unity::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Unidad dada de baja', 0.1);
        return redirect()->route('units.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function restore($id){
        Unity::withTrashed()->where('id', $id)->restore();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Unidad recuperada', 0.1);
        return redirect()->route('units.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
