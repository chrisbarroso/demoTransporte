<?php
namespace App\Http\Controllers\CuentasCorriente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NumerosEnLetras;
use Auth;
use App\Client;

class ClientesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $keywordRazonSocial = $request->searchRazonSocial;

        if(isset($keywordRazonSocial)) {
            $filter = Client::where([
                ['razonsocial', 'LIKE', "%$keywordRazonSocial%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(15, ['*'], 'clientes');
        }else {
            $filter = Client::orderBy('id','DESC')->paginate(15, ['*'], 'clientes');
        }

        $keywordCliente = $request->searchCliente;

        if(isset($keywordCliente)) {

            $filter2 = Client::findOrFail($keywordCliente);

            return view('cuentas-corriente.clientes', [
                'clientes' => $filter,
                'clienteCC' => $filter2,
            ]);
        }else {
            return view('cuentas-corriente.clientes', [
                'clientes' => $filter,
            ]);
        }

    }

}
