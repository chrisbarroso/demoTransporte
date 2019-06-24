@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

<div class="row" id="advancements">
  <div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Ordenes de compras registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($compras) > 0)
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Pedido por (chofer)</th>
              <th>Se liquida (Unidad)</th>
              <th>Unidad pertenece (Dueño)</th>
              <th>Proveedor</th>
              <th>Fecha</th>
              <th>Numero de control</th>
              <th>Confirmado</th>
              <th>Liquidado</th>
              <th>Acci&oacute;n</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($compras as $compra)
            <tr @if($compra->liquidado) style="background-color: #41ff65;" @endif>
              <td>{{ $compra->driverPur->nombre }} {{ $compra->driverPur->apellido }}</td>
              <td>{{ $compra->unityPur->dominio }}</td>
              <td>{{ $compra->ownerPur->nombre }} {{ $compra->ownerPur->apellido }}</td>
              <td>{{ $compra->providerPur->razonsocial }}</td>
              <td>{{ $compra->fecha->format('d/m/Y') }}</td>
              <td>{{ $compra->nro_control }}</td>
              <td>
                @if($compra->confirmado)
                  Si
                @else
                  No
                @endif
              </td>
              <td>
                @if($compra->liquidado)
                  Si
                @else
                  No
                @endif
              </td>
              <td>
                <a href="{{ route('purchases.show', [ 'purchase' => $compra->id ]) }}" class="label label-default">
                  <i class="fa fa-eye"></i> Ver
                </a>
                <br />
                <a class="label label-default" data-toggle="modal" data-target="#imprimir{{ $compra->id}}">
                  <i class="fa fa-print"></i> Imprimir
                </a>
                
                <!- ModalImpresion -->
                <div class="modal fade" id="imprimir{{ $compra->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body" id="imp{{ $compra->id}}" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                        <p style="float: right;"><img src="{{ asset('assets/img/logo-dark.png') }}" width="250px"></p>
                        <p>Alvear 731 - 2760 - San Antonio de Areco<br />
                        Tel: 02326 454800 - Cel: 02325 659825<br />
                        E-Mail: transguardapampa@yahoo.com.ar
                        </p>
                        <p>San Antonio de Areco, {{ $compra->fecha->format('d/m/Y') }}</p>
                        <p>Orden de compra numero: {{ $compra->id }}</p>
                        <hr style="margin-top: 5px;margin-bottom: 5px;" />
                        <p>Sr / es: {{ $compra->providerPur->razonsocial }}</p>
                        <p>Sirvase entregar al Sr/a: {{ $compra->driverPur->nombre }} {{ $compra->driverPur->apellido }} ({{ $compra->driverPur->cuit }})</p>
                        <p>Numero de control: <b>{{ $compra->nro_control }}</b></p>
                        <p>Camion: {{ $compra->unityPur->dominio }} - Acoplado: {{ $compra->unityPur->dominioAcoplado }}</p>
                        <hr style="margin-top: 5px;margin-bottom: 5px;" />
                        <p>Lo siguiente:</p>
                        <p>
                          @if($compra->tanque_lleno)
                          Tanque lleno
                          <br />
                          @endif
                          @if($compra->litros != null)
                          {{ $compra->litros }} lts gasoil
                          <br />
                          @endif
                          @if($compra->importe != null)
                          Por un importe de ${{ number_format($compra->importe, 2) }}
                          @endif
                        </p>
                        <p style="float: right;">
                        ------------------------------------------------------- <br />
                        Firma y aclaracion
                        </p>
                        <br />
                        <p>Original</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="prints({{$compra->id}});">
                          <i class="fa fa-print"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!- finModalImpresion -->
                @if(!$compra->liquidado)
                @if(!$compra->confirmado)
                <br />
                <a class="label label-success" data-toggle="modal" data-target="#confirmar{{ $compra->id}}">
                  <i class="fa fa-check"></i> Confirmar orden de compra
                </a>
                <!- ModalConfirmar -->
                <div class="modal fade" id="confirmar{{ $compra->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <form action="{{ route('purchases.confirmations', ['confirmation' => $compra->id])}}" method="POST" class="form-inline">
                      <div class="modal-body">
  
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="row">
                          <div class="form-group col-md-6 col-xs-12">
                              <label class="control-label">Litros</label>
                              <div class="">
                                  <input type="number" name="litros" value="{{ $compra->litros }}" class="form-control" required />
                              </div>
                          </div>

                          <div class="form-group col-md-6 col-xs-12">
                              <label class="control-label">Importe</label>
                              <div class="">
                                  <input type="number" name="importe" step="any" value="{{ $compra->importe }}" class="form-control" required />
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"></i> Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!- finModalConfirmar -->
                @endif
                <br />
                <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $compra->id}}">
                  <i class="fa fa-trash-o"></i> Dar de baja
                </a>
                <!- ModalEliminar -->
                <div class="modal fade" id="confirmarEliminar{{ $compra->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <form action="{{ route('purchases.destroy', ['purchase' => $compra->id])}}" method="POST" class="form-inline">
                      <div class="modal-body">
                        <h5>Estas seguro que quiere dar de baja esta orden de compra?</h5>
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"></i> Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!- finModalEliminar -->
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
          </table>
          {{ $compras->links() }}
        @else
          <p>No hay ordenes de compras registradas</p>
        @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
          <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar ordenes de compras dadas de baja</a>
            <a class="btn btn-primary" href="{{ route('purchases.create')}}">Registrar orden de compra</a>
          </div>
        </div>
      </div>

      <!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Ordenes de compra dadas de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($comprasEliminadas) > 0)
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nro de orden</th>
                    <th>Pedido por (chofer)</th>
                    <th>Proveedor</th>
                    <th>Nro de control</th>
                    <th>Fecha</th>
                    <th>Acci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($comprasEliminadas as $compraEliminada)
                  <tr>
                    <td>{{ $compraEliminada->id }}</td>
                    <td>{{ $compraEliminada->driverPur->nombre }} {{ $compraEliminada->driverPur->apellido }} </td>
                    <td>{{ $compraEliminada->providerPur->razonsocial }} </td>
                    <td>{{ $compraEliminada->nro_control }}</td>
                    <td>{{ $compraEliminada->fecha->format('d/m/Y') }}</td>
                    <td>
                      <a href=" {{ route('purchases.restore', [ 'purchase' => $compraEliminada->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay ordenes de compras para recuperar</p>
              @endif
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <!- finModalEliminados -->
    </div>
  </div>
</div>
@endsection

@section("scripts")
<script >
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