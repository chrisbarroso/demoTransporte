@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

<div class="row">
  <div class="col-md-3 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Filtros</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form action="{{ route('duenosP.index') }}" method="GET" class="">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="Nombre">Nombre:</label>
              <input type="text" name="searchNombre" class="form-control" value="{{ Request::get('searchNombre') }}">
            </div>
            <div class="form-group">
              <label for="Apellido">Apellido:</label>
              <input type="text" name="searchApellido" class="form-control" value="{{ Request::get('searchApellido') }}">
            </div>
            <button type="submit" class="btn btn-default">Filtrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Pagos realizados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($duenos) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-3">Nombre</th>
                <th class="col-sm-3">Apellido</th>
                <th class="col-sm-2">CUIT</th>
                <th class="col-sm-2">Tel&eacute;fono</th>
                <th class="col-sm-3">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($duenos as $dueno)
              <tr>
                <td>{{ $dueno->nombre }}</td>
                <td>{{ $dueno->apellido }}</td>
                <td>{{ $dueno->cuit }}</td>
                <td>{{ $dueno->tel }}</td>
                <td>
                  <a class="label label-success" data-toggle="modal" data-target="#pagar{{ $dueno->id}}">
                    <i class="fa fa-money"></i> Pagar
                  </a>
                  <div class="modal fade" id="pagar{{ $dueno->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-body" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <form action="{{ route('duenosP.guardado', ['id_dueno' => $dueno->id])}}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="row">
                              <div class="form-group col-md-6 col-xs-12">
                                  <button type="button" class="btn btn-primary" onClick="add({{ $dueno->id }});">Agregar forma de pago</button>
                              </div>
                            </div>
                            <div id="campos{{ $dueno->id }}"></div>
                            <div id="pagosTotales{{ $dueno->id }}"></div>
                            <div class="row">
                              <div class="form-group col-md-12 col-xs-12">
                                <button type="submit" id="enviar{{ $dueno->id }}" class="btn btn-success" disabled onClick="this.disabled=true;this.form.submit();">Pagar</button>
                                <a href="{{ route('duenosP.index') }}" type="button" class="btn btn-secondary">Cancelar</a>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $duenos->links() }}
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-3 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Filtros</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form action="{{ route('duenosP.index') }}" method="GET" class="">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="N&uacute;mero de comprobante">N&uacute;mero:</label>
              <input type="number" name="searchNumeroComprobantePagos" class="form-control" value="{{ Request::get('searchNumeroComprobantePagos') }}">
            </div>
            <div class="form-group">
              <label for="Nombre">Nombre:</label>
              <input type="text" name="searchNombreDuenoPagos" class="form-control" value="{{ Request::get('searchNombreDuenoPagos') }}">
            </div>
            <div class="form-group">
              <label for="Apellido">Apellido:</label>
              <input type="text" name="searchApellidoDuenoPagos" class="form-control" value="{{ Request::get('searchApellidoDuenoPagos') }}">
            </div>
            <div class="form-group">
              <label for="Fecha">Fecha:</label>
              <input type="date" name="searchFechaPagos" class="form-control" value="{{ Request::get('searchFechaPagos') }}">
            </div>
            <button type="submit" class="btn btn-default">Filtrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Pagos realizados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($pagos) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-3">N&uacute;mero</th>
                <th class="col-sm-2">Fecha</th>
                <th class="col-sm-3">Nombre</th>
                <th class="col-sm-3">Apellido</th>
                <th class="col-sm-3">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pagos as $pago)
              <tr>
                <td>{{ $pago->id }}</td>
                <td>{{ $pago->created_at->format('d/m/Y') }}</td>
                <td>{{ $pago->dueno->nombre }}</td>
                <td>{{ $pago->dueno->apellido }}</td>
                <td>
                  
                  <a class="label label-default" data-toggle="modal" data-target="#imprimir{{ $pago->id}}">
                    <i class="fa fa-print"></i> Imprimir
                  </a>
                
                  <!- ModalImpresion -->
                  <div class="modal fade" id="imprimir{{ $pago->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-body" id="imp{{ $pago->id}}" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <p style="float: right;"><img src="{{ asset('assets/img/logo-dark.png') }}" width="250px"></p>
                          <p>Alvear 731 - 2760 - San Antonio de Areco<br />
                          Tel: 02326 454800 - Cel: 02325 659825<br />
                          E-Mail: transguardapampa@yahoo.com.ar
                          </p>
                          <p>San Antonio de Areco, {{ $pago->created_at->format('d/m/Y') }}</p>
                          <p>Comprobante n&uacute;mero: {{ $pago->id }}</p>
                          <hr style="margin-top: 5px;margin-bottom: 5px;" />
                          <p>Comprobante de pago de: {{ $pago->dueno->razonsocial }} {{ $pago->dueno->apellido }} ({{ $pago->dueno->cuit }})</p>
                          <hr style="margin-top: 5px;margin-bottom: 5px;" />
                          <p>
                            <b>Fecha de pago:</b> {{ $pago->created_at->format('d/m/Y') }} <br />
                            <b>Formas de pago</b> <br /><br />
                            @foreach($pago->pagosFormas as $pagosFormas)
                              @if($pagosFormas->forma == "Efectivo")
                              <b>Forma:</b> {{ $pagosFormas->forma }} <br />
                              <b>Importe pagado:</b> ${{ number_format($pagosFormas->importe_efectivo, 2) }} <br />
                              <b>Importe en letras:</b> {!! NumerosEnLetras::convertir($pagosFormas->importe_efectivo, 'pesos', false, 'centavos') !!}<br />
                              <br />
                              @else
                              <b>Forma:</b> {{ $pagosFormas->forma }} - (N&uacute;mero: {{ $pagosFormas->cartera->nro_cheque }}) <br />
                              <b>Importe pagado:</b> ${{ number_format($pagosFormas->cartera->importeF, 2) }} <br />
                              <b>Importe en letras:</b> {!! NumerosEnLetras::convertir($pagosFormas->cartera->importeF, 'pesos', false, 'centavos') !!}<br />
                              <br />
                              @endif
                            @endforeach
                          </p>
                          <p></p>
                          <p style="float: right;">
                          ------------------------------------------------------- <br />
                          Firma y aclaraci&oacute;n
                          </p>
                          <br />
                          <p></p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" onclick="prints({{$pago->id}});">
                            <i class="fa fa-print"></i> Imprimir
                          </button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!- finModalImpresion -->

                  &nbsp;-&nbsp;

                  <a class="label label-danger" data-toggle="modal" data-target="#bajapago{{ $pago->id}}">
                    <i class="fa fa-money"></i> Dar de baja
                  </a>
                  <div class="modal fade" id="bajapago{{ $pago->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-body" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <form action="{{ route('duenosP.baja', ['id_pago' => $pago->id])}}" method="POST" class="">
                            {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="form-group">
                              <label for="Motivo">Motivo de baja:</label>
                              <textarea name="motivo_baja" class="form-control" required></textarea>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-12 col-xs-12">
                                <button type="submit" class="btn btn-success" onClick="this.disabled=true;this.form.submit();">Dar de baja</button>
                                <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancelar</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $pagos->links() }}
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Pagos dados de baja</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($pagosEliminados) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-2">N&uacute;mero</th>
                <th class="col-sm-2">Nombre</th>
                <th class="col-sm-2">Apellido</th>
                <th class="col-sm-2">Fecha dada de baja</th>
                <th class="col-sm-4">Motivo</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pagosEliminados as $pagoEliminado)
              <tr>
                <td>{{ $pagoEliminado->id }}</td>
                <td>{{ $pagoEliminado->dueno->nombre }}</td>
                <td>{{ $pagoEliminado->dueno->apellido }}</td>
                <td>{{ $pagoEliminado->deleted_at->format('d/m/Y') }}</td>
                <td>{{ $pagoEliminado->motivo_baja }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $pagosEliminados->links() }}
          @else
          <p>No hay pagos dados de baja</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section("scripts")
<script>


var entregas = 0;

function add($dueno_id) {
    entregas++; 
    document.getElementById("enviar"+$dueno_id).disabled = false;

    campo = '<div class="row" id="ID'+entregas+'"><div class="form-group col-md-3 col-xs-12"><label class="control-label">Entrega con</label><div class=""><select onchange="selectForma('+entregas+', this)" name="forma_pago'+entregas+'" class="form-control" required><option value="">Seleccionar entrega</option> <option value="Efectivo">Efectivo</option> <option value="Cheque">Cheque (Se quita de cartera)</option> </select></div></div></div>';
    $("#campos"+$dueno_id+"").append(campo);
    total = '<input type="hidden" name="totalFormasPagos" value="'+entregas+'">';
    $("#pagosTotales"+$dueno_id+"").html(total);     
}
function selectForma(campoid, selectObject) {
    
  var value = selectObject.value;
  if(value == "Efectivo") {
    campo2 = '<div id="camposPagos'+campoid+'"><div class="form-group col-md-8 col-xs-12"><label class="control-label">Importe en efectivo</label><input type="number" name="importe_efectivo'+campoid+'" step="any" value="" class="form-control" required /></div><div class="col-xs-12"><hr /></div></div>';
  }else if(value == "Cheque") {
    campo2 = '<div id="camposPagos'+campoid+'"><div class="form-group col-xs-12 col-md-3"><label class="control-label">N&uacute;mero de cheque</label><input type="number" name="nro_cheque'+campoid+'" class="form-control" required></div><div class="form-group col-xs-12 col-md-3"><label class="control-label">Importe</label><input type="number" name="importeF'+campoid+'" class="form-control" step="any" required></div><div class="form-group col-xs-12 col-md-3"><label class="control-label">Banco</label><select name="id_banco'+campoid+'" class="form-control" require><option value="" selected>Seleccionar banco</option>@foreach($bancos as $banco)<option value="{{ $banco->id }}">{{ $banco->nombre }}</option>@endforeach</select></div><div class="form-group col-xs-12 col-md-12"><label class="control-label">Obervacion</label><textarea name="observacion'+campoid+'" class="form-control"></textarea></div><div class="col-xs-12"><hr /></div></div>';
  }
  
  $("#camposPagos"+campoid).remove();
  $("#ID"+campoid).append(campo2);

}

function prints(id) {
  $('#imprimir'+id).modal('hide');
  var d = document.getElementById('imprimir'+id);
  d.style.display = 'none';
  $('.modal-backdrop').remove();

  var contenido = document.getElementById('imp'+id).innerHTML;
  var contenidoOriginal = document.body.innerHTML;
  document.body.innerHTML = contenido;
  
  window.print();
  document.body.innerHTML = contenidoOriginal;
};
</script>
@endsection

