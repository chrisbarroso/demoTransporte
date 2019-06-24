<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Controllers\Input;
use App\Wallet;
use App\Banco;

class WalletController extends Controller
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
    
    
    public function index(Request $request)
    {

        //Filtros
        $keywordNumeroCheque = $request->searchNumeroCheque;

        if(isset($keywordNumeroCheque)) {
            $filter = Wallet::where([
                ['nro_cheque', 'LIKE', "%$keywordNumeroCheque%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(15);
        }else{
            $filter = Wallet::
            orderBy('id','DESC')
            ->paginate(15);
        }

        return view('administration.wallet', (array) $request->view + [
            'cheques' => $filter,
            'chequesEliminados' => Wallet::onlyTrashed()->get()->sortByDesc('id'),
        ]); 
    }

    
    public function create()
    {
        return view('administration.wallet.form', [
            'create' => true,
            'cheque' => new Wallet(),
            'bancos' => Banco::get(),
        ]);
    }

   
    public function store(Request $request)
    {
        $valores = $request->all();

        $validator = \Validator::make($valores, [
            'nro_cheque' => 'required|unique:wallets',
        ]);
    
        if ($validator->fails()) {
            $AlertType = cookie('AlertType', 'danger', 0.1);
            $Msj = cookie('Msj', 'Este numero de cheque ya se encuentra cargado', 0.1);
            return redirect()->route('wallet.create')->withCookie($AlertType)->withCookie($Msj);
        }else {
            $cheque = new Wallet($valores);
            $cheque->save();

            $AlertType = cookie('AlertType', 'success', 0.1);
            $Msj = cookie('Msj', 'Cheque cargado correctamente', 0.1);
            return redirect()->route('wallet.index')->withCookie($AlertType)->withCookie($Msj);
        }
    }

    public function destroy($id)
    {
        Wallet::destroy($id);
        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Cheque dado de baja', 0.1);
        return redirect()->route('wallet.index')->withCookie($AlertType)->withCookie($Msj);
    }
 
}
