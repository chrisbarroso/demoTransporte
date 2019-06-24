<?php
namespace App\Http\Controllers\Liquidacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Client;
use App\Waybill;
use App\LiquidacionCliente;

class ClientesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $hoy = date('d/m/Y');

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
       
        $keywordFechaH = $request->searchFechaH;
        $keywordRazonSocialH = $request->searchRazonSocialH;

        if((isset($keywordFechaH)) || (isset($keywordRazonSocialH))) {
            $filter2 = LiquidacionCliente::where([
                ['liquidacion_cliente.created_at', 'LIKE', "%$keywordFechaH%"],
                ['clients.razonsocial', 'LIKE', "%$keywordRazonSocialH%"],
            ])
            ->join('clients', 'clients.id', '=', 'liquidacion_cliente.id_cliente')
            ->select('liquidacion_cliente.*')
            ->orderBy('liquidacion_cliente.id','DESC')
            ->paginate(15, ['*'], 'liquidacion');
        } else {
            $filter2 = LiquidacionCliente::orderBy('id','DESC')->paginate(15, ['*'], 'liquidacion');
        }

        return view('liquidaciones.clientes', [
            'hoy' => $hoy,
            'clientes' => $filter,
            'liquidaciones' => $filter2,
        ]);

    }

    public function guardado($id) {

        $csporteCuentas = Waybill::where([
            ['idCliente', '=', $id],
            ['liquidado_cliente', '=', 0],
            ['id_liquidado_cliente', '=', null],
        ])
        ->get();

        $liquidacion = new LiquidacionCliente();
        foreach ($csporteCuentas as $csporteCuenta) {
            $liquidacion->importe_viajes = $liquidacion->importe_viajes + $csporteCuenta->importe_cliente;
        }
        $liquidacion->importe_total = $liquidacion->importe_viajes;
        $liquidacion->id_cliente = $id;
        $liquidacion->concepto = "Liquidación";
        $liquidacion->motivo = null;
        $liquidacion->save();

        $csporte = Waybill::where([
            ['idCliente', '=', $id],
            ['liquidado_cliente', '=', 0],
            ['id_liquidado_cliente', '=', null],
        ])->update([
            'liquidado_cliente' => 1,
            'id_liquidado_cliente' => $liquidacion->id,
        ]);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Liquidacion correcta', 0.1);
        return redirect()->route('clientes.index')->withCookie($AlertType)->withCookie($Msj);

    }
  
}
