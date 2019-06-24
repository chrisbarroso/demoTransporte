@extends('layouts.app') @section('content') 
<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="col-md-12 col-xs-12">
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$adelanto->id}}</h3>
      <p class="panel-subtitle">{{$create ? 'Generar nuevo adelanto' : 'Modificar datos del adelanto numero '.$adelanto->id}}</p>
      @if(!$create)
        <p>Chofer: {{ $adelanto->driverAde->nombre }} {{ $adelanto->driverAde->apellido }} ({{ $adelanto->driverAde->cuit }})<br />
        Unidad -> Dominio: {{ $adelanto->unityAde->dominio }} - Dominio Acoplado: {{ $adelanto->unityAde->dominioAcoplado }}<br />
        Dueño de la unidad: {{ $adelanto->ownerAde->nombre }} {{ $adelanto->ownerAde->apellido }} ({{ $adelanto->ownerAde->cuit }})<br />
        Importe total: ${{ number_format($adelanto->importe_total, 2) }}
      </p>
     
        @endif
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">

          <form action="{{ $create ? route('advancements.store') : route('advancements.update', ['advancement' => $adelanto->id])}}" method="POST">
            {{ csrf_field() }} {{ method_field($create ? 'POST' : 'PUT') }}

            @if($create)
            <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Chofer / Unidad / Dueño</label>
                <div class="">
                    <select name="id_chofer" class="form-control" required>
                        <option value="">Seleccionar chofer</option>
                        @foreach ($choferes as $chofer)
                          @if($chofer->idUnidad != null)
                            <option value="{{ $chofer->id }}" @if ($adelanto->id_chofer == $chofer->id) selected @endif>{{ $chofer->nombre }} {{ $chofer->apellido }} ({{ $chofer->cuit }}) / {{ $chofer->unity->dominio }} / {{ $chofer->unity->owner->nombre }} {{ $chofer->unity->owner->apellido }} ({{ $chofer->unity->owner->cuit }})</option>
                          @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Fecha</label>
                <div class="">
                  @if($create)
                    <input type="date" name="fecha" class="form-control" required />
                  @else
                    <input type="date" name="fecha" value="{{ $adelanto->fecha->format('Y-m-d') }}" class="form-control" required />
                  @endif
                </div>
            </div>

            @if((!$create) && (count($pagosSalida) > 0))
              @foreach ($pagosSalida as $pagoSalida)
                @if($pagoSalida->forma_pago == "Efectivo")
                  <div class="form-group col-md-12 col-xs-12">
                    <label class="control-label">Pago entregado con: </label><br />
                    <label class="control-label">
                      <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $pagoSalida->id}}">
                        <i class="fa fa-trash-o"></i> Eliminar
                      </a>
                    </label>
                    <div class="">
                        <input disabled type="text" value="{{ $pagoSalida->forma_pago }} - ${{ number_format($pagoSalida->importe_efectivo, 2) }}" class="form-control" />
                    </div>
                  </div>
                @endif
                
                @if($pagoSalida->id_forma_pago == 2)
                  <div class="form-group col-md-12 col-xs-12">
                    <label class="control-label">Pago entregado con: </label><br />
                    <label class="control-label">
                      <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $pagoSalida->id}}">
                        <i class="fa fa-trash-o"></i> Eliminar
                      </a>
                    </label>
                    
                    <div class="">
                        <input disabled type="text" value="{{ $pagoSalida->formPaymentOut->detalle }} - ${{ number_format($pagoSalida->importeF, 2) }} - numero de cheque: {{ $pagoSalida->nro_cheque }}" class="form-control" />
                    </div>
                  </div>
                @endif

                @if($pagoSalida->id_forma_pago == 3)
                  <div class="form-group col-md-12 col-xs-12">
                    <label class="control-label">Pago entregado con: </label><br />
                    <label class="control-label">
                      <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $pagoSalida->id}}">
                        <i class="fa fa-trash-o"></i> Eliminar
                      </a>
                    </label>
                    <div class="">
                        <input disabled type="text" value="{{ $pagoSalida->formPaymentOut->detalle }} - ${{ number_format($pagoSalida->walletOut->importeF, 2) }} - numero de cheque: {{ $pagoSalida->walletOut->nro_cheque }}" class="form-control" />
                    </div>
                  </div>
                @endif
              @endforeach
            @endif

          
            <div class="row">
              <div class="form-group col-md-12 col-xs-12">
                  <button type="button" class="btn btn-primary" onClick="add();">Agregar forma de entrega</button>
              </div>
            </div>
            
            <div id="campos"></div>
            <div id="entregasTotales"></div>

            <div class="form-group col-md-12 col-xs-12">
              <button type="submit" id="enviar" class="btn btn-success" {{$create ? 'disabled' : ''}}>{{$create ? 'Crear' : 'Editar'}} adelanto</button>
              <a href="{{ route('advancements.index') }}" class="btn btn-default">Cancelar</a>
            </div>
          </form>

          @if((!$create) && (count($pagosSalida) > 0))
          @foreach ($pagosSalida as $pagoSalida)
          <div class="modal fade" id="confirmarEliminar{{ $pagoSalida->id}}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <form action="{{ route('advancementsout.destroy', ['advancementout' => $pagoSalida->id])}}" method="POST" class="form-inline">
                          <div class="modal-body">
                          <h5>Estas seguro que quiere eliminar esta entrega de pago?</h5>
                          {{ csrf_field() }}
                          {{ method_field('DELETE') }}
                            <input type="hidden" name="volver" value="adelanto" />
                            <input type="hidden" name="idvolver" value="{{ $adelanto->id }}" />
                          </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"></i> Confirmar</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          @endforeach
          @endif


        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section("scripts")
<script src="{{ mix('js/general.form.js') }}"></script>
<script>
var entregas = 0;

function add() {
    entregas++; 
    document.getElementById("enviar").disabled = false;

    campo = '<div class="row" id="ID'+entregas+'"><div class="form-group col-md-6 col-xs-12"><label class="control-label">Entregado con</label><div class=""><select onchange="selectForma('+entregas+', this)" name="forma_pago'+entregas+'" class="form-control" required><option value="">Seleccionar entrega</option><option value="Efectivo">Efectivo</option></select></div></div></div>';
    $("#campos").append(campo);
    total = '<input type="hidden" name="totalFormasPagos" value="'+entregas+'" >';
    $("#entregasTotales").html(total);
}

function selectForma(campoid, selectObject) {
  var value = selectObject.value;  
  if(value == "Efectivo") {
    campo2 = '<div id="camposPagos'+campoid+'"><div class="form-group col-md-6 col-xs-12"><label class="control-label">Importe en efectivo</label><input type="number" name="importe_efectivo'+campoid+'" step="any" value="" class="form-control" required /></div></div>';
  }else if(value == 2) {
    campo2 = '<div id="camposPagos'+campoid+'"><div class="form-group col-md-3 col-xs-12"><label class="control-label">Importe del cheque</label><input type="number" name="importeF'+campoid+'" step="any" value="" class="form-control" required /></div><div class="form-group col-md-3 col-xs-12"><label class="control-label">Numero de cheque</label><input type="number" name="nro_cheque'+campoid+'"  value="" class="form-control" /></div></div>';
  }else if(value == 3) {
    campo2 = '<div id="camposPagos'+campoid+'"><div class="form-group col-md-6 col-xs-12"><label class="control-label">Seleccion en cartera</label><select name="id_cartera'+entregas+'" class="form-control" required><option value="">Seleccionar en cartera</option>@foreach ($carteras as $cartera)@if(!$cartera->dado)<option value="{{ $cartera->id }}">Numero de cheque: {{ $cartera->nro_cheque }} - ${{ number_format($cartera->importeF, 2) }}</option>@endif @endforeach</select></div></div>';
  }
  
  $("#camposPagos"+campoid).remove();
  $("#ID"+campoid).append(campo2);

}
</script>
@endsection