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
          <form action="{{ route('clientesC.index') }}" method="GET" class="">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="Raz&oacute;n Social">Raz&oacute;n Social:</label>
              <input type="text" name="searchRazonSocial" class="form-control" value="{{ Request::get('searchRazonSocial') }}">
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
        <h3 class="panel-title">Cobranzas por cliente</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($clientes) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-3">Raz&oacute;n Social</th>
                <th class="col-sm-2">CUIT</th>
                <th class="col-sm-2">Tel&eacute;fono</th>
                <th class="col-sm-3">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($clientes as $cliente)
              <tr>
                <td>{{ $cliente->razonsocial }}</td>
                <td>{{ $cliente->cuit }}</td>
                <td>{{ $cliente->tel }}</td>
                <td>
                  <a class="label label-success" data-toggle="modal" data-target="#cobrar{{ $cliente->id}}">
                    <i class="fa fa-money"></i> Cobrar
                  </a>
                  <div class="modal fade" id="cobrar{{ $cliente->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-body" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <form action="{{ route('clientesC.guardado', ['id_cliente' => $cliente->id])}}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="row">
                              <div class="form-group col-md-6 col-xs-12">
                                  <button type="button" class="btn btn-primary" onClick="add({{ $cliente->id }});">Agregar forma de entrega</button>
                              </div>
                            </div>
                            <div id="campos{{ $cliente->id }}"></div>
                            <div id="entregasTotales{{ $cliente->id }}"></div>
                            <div class="row">
                              <div class="form-group col-md-12 col-xs-12">
                                <button type="submit" id="enviar{{ $cliente->id }}" class="btn btn-success" disabled onClick="this.disabled=true;this.form.submit();">Cobrar</button>
                                <a href="{{ route('clientesC.index') }}" type="button" class="btn btn-secondary">Cancelar</a>
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
          {{ $clientes->links() }}
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
          <form action="{{ route('clientesC.index') }}" method="GET" class="">
            {{ csrf_field() }}
            <div class="form-group">
            <label for="N&uacute;mero de comprobante">N&uacute;umero:</label>
              <input type="number" name="searchNumeroComprobantePagos" class="form-control" value="{{ Request::get('searchNumeroComprobantePagos') }}">
            </div>
            <div class="form-group">
            <label for="Raz&oacute;n Social">Raz&oacute;n Social:</label>
              <input type="text" name="searchRSPagos" class="form-control" value="{{ Request::get('searchRSPagos') }}">
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
        <h3 class="panel-title">Cobranzas realizadas</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($cobranzas) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-3">N&uacute;mero</th>
                <th class="col-sm-2">Fecha</th>
                <th class="col-sm-3">Raz&oacute;n Social</th>
                
                <th class="col-sm-3">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cobranzas as $cobranza)
              <tr>
                <td>{{ $cobranza->id }}</td>
                <td>{{ $cobranza->created_at->format('d/m/Y') }}</td>
                <td>{{ $cobranza->cliente->razonsocial }}</td>
                <td>
                  
                  <a class="label label-default" data-toggle="modal" data-target="#imprimir{{ $cobranza->id}}">
                    <i class="fa fa-print"></i> Imprimir
                  </a>
                
                  <!- ModalImpresion -->
                  <div class="modal fade" id="imprimir{{ $cobranza->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-body" id="imp{{ $cobranza->id}}" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <p style="float: right;"><img src="{{ asset('assets/img/logo-dark.png') }}" width="250px"></p>
                          <p>Alvear 731 - 2760 - San Antonio de Areco<br />
                          Tel: 02326 454800 - Cel: 02325 659825<br />
                          E-Mail: transguardapampa@yahoo.com.ar
                          </p>
                          <p>San Antonio de Areco, {{ $cobranza->created_at->format('d/m/Y') }}</p>
                          <p>Comprobante n&uacute;mero: {{ $cobranza->id }}</p>
                          <hr style="margin-top: 5px;margin-bottom: 5px;" />
                          <p>Comprobante de pago de: {{ $cobranza->cliente->razonsocial }} ({{ $cobranza->cliente->cuit }})</p>
                          <hr style="margin-top: 5px;margin-bottom: 5px;" />
                          <p>
                            <b>Fecha de pago:</b> {{ $cobranza->created_at->format('d/m/Y') }} <br />
                            <b>Formas de pago</b> <br /><br />
                            @foreach($cobranza->cobranzasFormas as $cobranzaFormas)
                              @if($cobranzaFormas->forma == "Efectivo")
                              <b>Forma:</b> {{ $cobranzaFormas->forma }} <br />
                              <b>Importe pagado:</b> ${{ number_format($cobranzaFormas->importe_efectivo, 2) }} <br />
                              <b>Importe en letras:</b> {!! NumerosEnLetras::convertir($cobranzaFormas->importe_efectivo, 'pesos', false, 'centavos') !!}<br />
                              <br />
                              @else
                              <b>Forma:</b> {{ $cobranzaFormas->forma }} - (N&uacute;mero: {{ $cobranzaFormas->cartera->nro_cheque }}) <br />
                              <b>Importe pagado:</b> ${{ number_format($cobranzaFormas->cartera->importeF, 2) }} <br />
                              <b>Importe en letras:</b> {!! NumerosEnLetras::convertir($cobranzaFormas->cartera->importeF, 'pesos', false, 'centavos') !!}<br />
                              <br />
                              @endif
                            @endforeach
                          </p>
                          <p></p>
                          <p style="float: right;">
                          ------------------------------------------------------- <br />
                          Firma y aclaracion
                          </p>
                          <br />
                          <p></p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" onclick="prints({{$cobranza->id}});">
                            <i class="fa fa-print"></i> Imprimir
                          </button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!- finModalImpresion -->

                  &nbsp;-&nbsp;

                  <a class="label label-danger" data-toggle="modal" data-target="#bajacobro{{ $cobranza->id}}">
                    <i class="fa fa-money"></i> Dar de baja
                  </a>
                  <div class="modal fade" id="bajacobro{{ $cobranza->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-body" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <form action="{{ route('clientesC.baja', ['id_cobranza' => $cobranza->id])}}" method="POST" class="">
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
          {{ $cobranzas->links() }}
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
        <h3 class="panel-title">Cobranzas dadas de baja</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($cobranzasEliminadas) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-2">N&uacute;mero</th>
                <th class="col-sm-2">Raz&oacute;n Social</th>
                <th class="col-sm-2">Fecha dada de baja</th>
                <th class="col-sm-4">Motivo</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cobranzasEliminadas as $cobranzaEliminada)
              <tr>
                <td>{{ $cobranzaEliminada->id }}</td>
                <td>{{ $cobranzaEliminada->cliente->razonsocial }}</td>
                <td>{{ $cobranzaEliminada->deleted_at->format('d/m/Y') }}</td>
                <td>{{ $cobranzaEliminada->motivo_baja }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $cobranzasEliminadas->links() }}
          @else
          <p>No hay cobranzas dadas de baja</p>
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

function add($cliente_id) {
    entregas++; 
    document.getElementById("enviar"+$cliente_id).disabled = false;

    campo = '<div class="row" id="ID'+entregas+'"><div class="form-group col-md-3 col-xs-12"><label class="control-label">Entrega con</label><div class=""><select onchange="selectForma('+entregas+', this)" name="forma_pago'+entregas+'" class="form-control" required><option value="">Seleccionar entrega</option> <option value="Efectivo">Efectivo</option> <option value="Cheque">Cheque (Se agrega a cartera)</option> </select></div></div></div>';
    $("#campos"+$cliente_id+"").append(campo);
    total = '<input type="hidden" name="totalFormasPagos" value="'+entregas+'">';
    $("#entregasTotales"+$cliente_id+"").html(total);     
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

