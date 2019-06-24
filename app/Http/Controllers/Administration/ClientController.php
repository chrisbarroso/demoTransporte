<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Controllers\Input;
use App\Client;

class ClientController extends Controller
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
        // $params = \Route::current()->parameters(); // De aca viene company_id
        //$filters = $request->except(['order_by', 'direction', 'paginate', 'page', 'onlyTrashed', 'withTrashed']);

        // Por defecto traemos los resultados naturales
        // $resources = \App\Client::where([]);

        // if ($request->has('onlyTrashed')) {
        //     $resources = \App\User::onlyTrashed();
        // } elseif ($request->has('withTrashed')) {
        //     $resources = \App\User::withTrashed();
        // }

        // $resources->where($params);
        // $resources->where($filters);

        // if ($request->has('order_by') || $request->has('direction')) {
        //     $orderBy = $request->input('order_by', 'id');
        //     $direction = $request->input('direction', 'asc');
        //     $resources = $resources->orderBy($orderBy, $direction);
        // }

        // if ($request->has('paginate') && $request->paginate > 0) {
        //     $paginate = $request->input('paginate', 25);

        //     return $resources->paginate($paginate);
        // }
        
        //Filtros
        $keywordRazonSocial = $request->searchRazonSocial;
        //$keywordCuit = $request->searchCuit;

        //if(isset($keywordRazonSocial) || isset($keywordCuit)){
        if(isset($keywordRazonSocial)) {
            $filter = Client::where([
                ['razonsocial', 'LIKE', "%$keywordRazonSocial%"],
                //['cuit', 'LIKE', "%$keywordCuit%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(15);
        }else{
            $filter = Client::
            orderBy('id','DESC')
            ->paginate(15);
        }

        // Paso la data de la vista y le sumo la data del metodo
        // el + en los arreglos prioriza el valor de la izquierda por el de la derecha si ya existe
        return view('administration.clients', (array) $request->view + [
            'clientes' => $filter,
            'clientesEliminados' => Client::onlyTrashed()->get()->sortByDesc('id'),
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.clients.form', [
            'create' => true,
            'cliente' => new Client(),
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
            'cuit' => 'required|string|max:13|unique:clients',
        ]);
    
        if ($validator->fails()) {
            $AlertType = cookie('AlertType', 'danger', 0.1);
            $Msj = cookie('Msj', 'Este cuit ya se encuentra cargado', 0.1);
            return redirect()->route('clients.create')->withCookie($AlertType)->withCookie($Msj);
        }else {
            $client = new Client($valores);
            $client->saldo = 0;
            $client->save();

            $AlertType = cookie('AlertType', 'success', 0.1);
            $Msj = cookie('Msj', 'Cliente cargado correctamente', 0.1);
            return redirect()->route('clients.index')->withCookie($AlertType)->withCookie($Msj);
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
        $client = Client::findOrFail($id);
        return view('administration.clients.view', [
            'cliente' => $client,
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
        $client = Client::findOrFail($id);
        return view('administration.clients.form', [
            'create' => false,
            'cliente' => $client,
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
        
        $client = Client::findOrFail($id);
        $client->fill($valores);
        $client->save();

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Cliente modificado', 0.1);
        return redirect()->route('clients.index')->withCookie($AlertType)->withCookie($Msj);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Client::destroy($id);
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Cliente dado de baja', 0.1);
        return redirect()->route('clients.index')->withCookie($AlertType)->withCookie($Msj);
    }
    
    public function restore($id){
        Client::withTrashed()->where('id', $id)->restore();
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Cliente recuperado', 0.1);
        return redirect()->route('clients.index')->withCookie($AlertType)->withCookie($Msj);
    }
}
