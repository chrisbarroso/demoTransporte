<?php
namespace App\Http\Controllers\Liquidacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\OwnerUnity;
use App\Waybill;
use App\LiquidacionDueno;

class DuenoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $hoy = date('d/m/Y');

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

        $keywordFechaH = $request->searchFechaH;
        $keywordNombreH = $request->searchNombreH;
        $keywordApellidoH = $request->searchApellidoH;

        if((isset($keywordFechaH)) || (isset($keywordNombreH)) || (isset($keywordApellidoH))) {
            $filter2 = LiquidacionDueno::where([
                ['liquidacion_dueno.created_at', 'LIKE', "%$keywordFechaH%"],
                ['owners_units.nombre', 'LIKE', "%$keywordNombreH%"],
                ['owners_units.apellido', 'LIKE', "%$keywordApellidoH%"],
            ])
            ->join('owners_units', 'owners_units.id', '=', 'liquidacion_dueno.id_dueno')
            ->select('liquidacion_dueno.*')
            ->orderBy('liquidacion_dueno.id','DESC')
            ->paginate(15, ['*'], 'liquidacion');
        }else {
            $filter2 = LiquidacionDueno::orderBy('id','DESC')->paginate(15, ['*'], 'liquidacion');
        }

        return view('liquidaciones.dueno', [
            'hoy' => $hoy,
            'duenos' => $filter,
            'liquidaciones' => $filter2,
        ]);
    }

    public function guardado($id) {

        $hoy = date('Y-m-d');
       
        $csporteCuentas = Waybill::where([
            ['id_dueno', '=', $id],
            ['liquidado_dueno', '=', 0],
            ['id_liquidacion_dueno', '=', null],
        ])
        ->get();
        
        $liquidacion = new LiquidacionDueno();
        foreach ($csporteCuentas as $csporteCuenta) {
            $liquidacion->importe_viajes = $liquidacion->importe_viajes + $csporteCuenta->importe_transporte;
            $liquidacion->importe_comision = $liquidacion->importe_comision + $csporteCuenta->importe_porcentaje;
        }
        $liquidacion->importe_total = $liquidacion->importe_viajes - $liquidacion->importe_comision;
        $liquidacion->id_dueno = $id;
        $liquidacion->save();

        $csporte = Waybill::where([
            ['id_dueno', '=', $id],
            ['liquidado_dueno', '=', 0],
            ['id_liquidacion_dueno', '=', null],
        ])->update([
            'liquidado_dueno' => 1,
            'id_liquidacion_dueno' => $liquidacion->id,
        ]);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Liquidacion correcta', 0.1);
        return redirect()->route('dueno.index')->withCookie($AlertType)->withCookie($Msj);
        
    }
}
