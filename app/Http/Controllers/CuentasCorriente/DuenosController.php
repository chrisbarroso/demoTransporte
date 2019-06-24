<?php
namespace App\Http\Controllers\CuentasCorriente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NumerosEnLetras;
use Auth;
use App\OwnerUnity;

class DuenosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $keywordNombre = $request->searchNombre;
        $keywordApellido = $request->searchApellido;

        if((isset($keywordNombre)) || (isset($keywordApellido))) {
            $filter = OwnerUnity::where([
                ['nombre', 'LIKE', "%$keywordNombre%"],
                ['apellido', 'LIKE', "%$keywordApellido%"],
            ])
            ->orderBy('id','DESC')
            ->paginate(15, ['*'], 'duenos');
        }else {
            $filter = OwnerUnity::orderBy('id','DESC')->paginate(15, ['*'], 'duenos');
        }

        $keywordDueno = $request->searchDueno;

        if(isset($keywordDueno)) {

            $filter2 = OwnerUnity::findOrFail($keywordDueno);

            return view('cuentas-corriente.duenos', [
                'duenos' => $filter,
                'duenoCC' => $filter2,
            ]);
        }else {
            return view('cuentas-corriente.duenos', [
                'duenos' => $filter,
            ]);
        }

    }

}
