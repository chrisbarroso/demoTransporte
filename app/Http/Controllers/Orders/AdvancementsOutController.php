<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Advancement;
use App\AdvancementOut;
use App\Wallet;

class AdvancementsOutController extends Controller
{
 
    public function destroy(Request $request, $id)
    {
        $AdvancementOut = AdvancementOut::findOrFail($id);

        $Advancement = Advancement::findOrFail($request->idvolver);
        if($AdvancementOut->forma_pago == "Efectivo") {
            $Advancement->importe_total = $Advancement->importe_total - $AdvancementOut->importe_efectivo;
        }
        $Advancement->save();

        

        /*if($AdvancementOut->id_cartera != null) {
            //dado
            $walletDado = Wallet::findOrFail($AdvancementOut->id_cartera);
            $walletDado->dado = 0;
            $walletDado->save();
        }*/
        
        AdvancementOut::destroy($id);

        $AlertType = cookie('AlertType', 'success', 0.1);
        $Msj = cookie('Msj', 'Entrega de pago dada de baja', 0.1);
        if($request->volver == "adelanto") {
            return redirect()->route('advancements.edit', ['advancement' => $request->idvolver])->withCookie($AlertType)->withCookie($Msj);
        }
    }

    public function restore($id){
        
    }
}
