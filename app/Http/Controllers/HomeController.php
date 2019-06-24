<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Driver;
use App\Unity;
use App\OwnerUnity;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cantidadClientes = Client::count();
        $cantidadChoferes = Driver::count();
        $cantidadUnidades = Unity::count();
        $cantidadDuenos = OwnerUnity::count();
        return view('home', [
            'clientes' => $cantidadClientes,
            'choferes' => $cantidadChoferes,
            'unidades' => $cantidadUnidades,
            'duenos' => $cantidadDuenos,
            // 'proveedores' => Providers::count(),
        ]);
    }
}
