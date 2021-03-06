<?php
namespace App\Http\Controllers\CobranzasPagos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NumerosEnLetras;

use Auth;

use App\Client;

use App\FormPayment;
use App\Banco;

use App\CobranzasClientes;
use App\CobranzasClientesFormas;
use App\Wallet;



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

        $keywordNumeroComprobantePagos = $request->searchNumeroComprobantePagos;
        $keywordRSPagos = $request->searchRSPagos;
        $keywordFechaPagos = $request->searchFechaPagos;
        
        if((isset($keywordFechaPagos)) || (isset($keywordNumeroComprobantePagos)) || (isset($keywordRSPagos))) {

            $filterPagos = CobranzasClientes::where([
                ['cobranzas_clientes.id', 'LIKE', "%$keywordNumeroComprobantePagos%"],
                ['cobranzas_clientes.created_at', 'LIKE', "%$keywordFechaPagos%"],
                ['clients.razonsocial', 'LIKE', "%$keywordRSPagos%"],
            ])
            ->join('clients', 'clients.id', '=', 'cobranzas_clientes.cliente_id')
            ->select('cobranzas_clientes.*')
            ->orderBy('cobranzas_clientes.created_at','DESC')
            ->paginate(15, ['*'], 'pagos');

        }else {
            $filterPagos = CobranzasClientes::orderBy('created_at','DESC')->paginate(15, ['*'], 'pagos');
        }

        return view('cobranzas-pagos.clientes', [
            'clientes' => $filter,
            'bancos' => Banco::get()->sortByDesc('id'),
            'cobranzas' => $filterPagos,
            'cobranzasEliminadas' => CobranzasClientes::onlyTrashed()->paginate(15, ['*'], 'cobranzasEliminadas'),
        ]);
    }

    public function guardado(Request $request, $id) {

        for($i = 1; $i <= $request->totalFormasPagos; $i++) {
            $forma_pago = "forma_pago".$i;
            if($request->$forma_pago == "") {
                $AlertType = cookie('AlertType', 'danger', 0.1);
                $Msj = cookie('Msj', 'Completar todos los campos', 0.1);
                return redirect()->route('clientesC.index')->withCookie($AlertType)->withCookie($Msj);
            }
        }

        //Suma para el total
        $total = 0;
        for($e = 1; $e <= $request->totalFormasPagos; $e++) {
            $forma_pagoT = "forma_pago".$e;
            if($request->$forma_pagoT == "Efectivo") {
                $importe_efectivoT = "importe_efectivo".$e;
                $total = $total + $request->$importe_efectivoT;
            }else if($request->$forma_pagoT == "Cheque") {
                $importeFT = "importeF".$e;
                $total = $total + $request->$importeFT;
            }
        }

        $cobranzasclientes = new CobranzasClientes;
        $cobranzasclientes->cliente_id = $id;
        $cobranzasclientes->total = $total;
        $cobranzasclientes->total = $total;
        $cobranzasclientes->motivo = null;
        $cobranzasclientes->concepto = "Cobranza realizada";
        $cobranzasclientes->motivo_baja = null;
        $cobranzasclientes->save();

        for($i = 1; $i <= $request->totalFormasPagos; $i++) {

            $forma_pago = "forma_pago".$i;

            $cobranzasclientesformas = new CobranzasClientesFormas;
            $cobranzasclientesformas->id_cobranza_cliente = $cobranzasclientes->id;
            $cobranzasclientesformas->forma = $request->$forma_pago;

            if($request->$forma_pago == "Efectivo") {
                $importe_efectivo = "importe_efectivo".$i;
                $cobranzasclientesformas->importe_efectivo = $request->$importe_efectivo;
                $cobranzasclientesformas->cartera_id = null;
            } else if ($request->$forma_pago == "Cheque") {
                /*$request->request->add(['nro_cheque' => $request['nro_cheque'.$i]]); 
                $validator = \Validator::make($request->all(), [
                    'nro_cheque' => 'required|unique:wallets',
                ]);

                if ($validator->fails()) {
                    $AlertType = cookie('AlertType', 'danger', 0.1);
                    $Msj = cookie('Msj', 'Este numero de cheque ya se encuentra cargado', 0.1);
                    return redirect()->route('clientesC.index')->withCookie($AlertType)->withCookie($Msj);
                } else {*/
                    
                    $importeF = "importeF".$i;
                    $nro_cheque = "nro_cheque".$i;
                    $observacion = "observacion".$i;
                    $id_banco = "id_banco".$i;

                    $cheque = new Wallet;
                    $cheque->importeF = $request->$importeF;
                    $cheque->nro_cheque = $request->$nro_cheque;
                    $cheque->observacion = $request->$observacion;
                    $cheque->id_banco = $request->$id_banco;
                    $cheque->dado = 0;
                    $cheque->save();

                    $cobranzasclientesformas->importe_efectivo = null;
                    $cobranzasclientesformas->cartera_id = $cheque->id;
                //}
            }

            $cobranzasclientesformas->save();
        }

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Cobranza realizada', 0.1);
        return redirect()->route('clientesC.index')->withCookie($AlertType)->withCookie($Msj);
    }

    public function baja(Request $request, $id) { 
        
        $CobranzasClientes = CobranzasClientes::findOrFail($id);
       
        foreach($CobranzasClientes->cobranzasFormas as $cobranzaForma) {
            if($cobranzaForma->forma == "Cheque") {
                Wallet::destroy($cobranzaForma->cartera_id);
            }
        }
        
        $CobranzasClientes->motivo_baja = $request->motivo_baja;
        $CobranzasClientes->save();

        CobranzasClientes::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Cobranza dada de baja', 0.1);
        return redirect()->route('clientesC.index')->withCookie($AlertType)->withCookie($Msj);
    }

}
