@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

<div class="row">
  <div class="col-md-4 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Filtros</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form action="{{ route('clientes.index') }}" method="GET" class="">
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
  <div class="col-md-8 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Liquidacion por cliente</h3>
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
                <th class="col-sm-3">CUIT</th>
                <th class="col-sm-3">Tel&eacute;fono</th>
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
                 
                  <a class="label label-success" data-toggle="modal" data-target="#Viajes{{ $cliente->id}}">
                    <i class="fa fa-info-circle"></i> Liquidar
                  </a>
                  @php
                  ${"tiene-" . $cliente->id} = 0;
                  ${"subtotalViajes-" . $cliente->id} = 0;
                  ${"total-" . $cliente->id} = 0;
                  $ultimodominio = "";
                  @endphp
                  <!- ModalInformes -->
                  <div class="modal fade" id="Viajes{{ $cliente->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-body" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          
                          <table width="100%">
                            <tbody>
                              <tr>
                                <td>
                                  <p>ACUÑA HERMANOS S.A</p>
                                  <p>Alvear 731 - 2760 - San Antonio de Areco <br />
                                  Tel: 02326-454800 - Cel: 2325-651793
                                  </p>
                                </td>
                                <td align="right">
                                  <p>Liquidaci&oacute;n <br />
                                  Fecha: {{ $hoy }}
                                  </p>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2"><hr /></td>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <p>
                                  Liquidaci&oacute;n de {{ $cliente->razonsocial }} a favor de ACUÑA HERMANOS S.A <br />
                                  Por acarreos segun vales detallados a continuación - Hasta fecha {{ $hoy }}
                                  </p>
                                </td>
                              </tr>
                            </tbody>
                          </table>

                          <table class="table table-striped" style="font-size: 14px;">
                            <thead>
                              <tr>
                                <th>Fecha</th>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>N° C.Porte</th>
                                <th>Merca</th>
                                <th>Tarifa</th>
                                <th>Kilometros</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($cliente->viajesLiquidadar as $viaje)
                              
                              @php
                              ${"tiene-" . $cliente->id} = 1;
                              ${"subtotalViajes-" . $cliente->id} =  ${"subtotalViajes-" . $cliente->id} + $viaje->importe_cliente;
                              @endphp
                              <tr>
                                <td>{{ $viaje->fecha->format('d/m/Y') }}</td>
                                <td>{{ $viaje->placeDeparture->lugar }}</td>
                                <td>{{ $viaje->placeDestination->lugar }}</td>
                                <td>{{ $viaje->ncporte }}</td>
                                <td>{{ $viaje->merca->nombre_merca }}</td>
                                <td>{{ $viaje->tarifa_cliente }}</td>
                                <td>{{ $viaje->kilometro }}</td>
                                <td>{{ $viaje->kilo }}</td>
                                <td>${{ number_format($viaje->importe_cliente, 2) }}</td>
                              </tr>
                            
                              @endforeach
                              <tr>
                                <td colspan="8">
                                  <b>Total de viajes</b>
                                </td>
                                <td>
                                  @php
                                  ${"total-" . $cliente->id} = ${"subtotalViajes-" . $cliente->id};
                                  @endphp
                                  ${{ number_format(${"total-" . $cliente->id}, 2) }}
                                </td>
                              </tr>

                              <tr>
                                <td colspan="8">
                                  <b>TOTAL</b>
                                </td>
                                <td>
                                  <b>${{ number_format(${"total-" . $cliente->id}, 2) }}</b>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          
                        </div>
                        <div class="modal-footer">
                          @if(${"tiene-" . $cliente->id})
                          <form action="{{ route('clientes.guardado', ['id_cliente' => $cliente->id])}}" method="PUT">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <button type="send" class="btn btn-success" onClick="this.disabled=true;this.form.submit();">Liquidar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          </form> 
                          @else
                          <button type="submit" class="btn btn-secondary" disabled>No tiene viajes para Liquidar</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  <!- finModalInformes -->
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
  <div class="col-md-4 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Filtros</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form action="{{ route('clientes.index') }}" method="GET" class="">
              {{ csrf_field() }}
            <div class="form-group">
              <label for="Fecha">Fecha:</label>
              <input type="date" name="searchFechaH" class="form-control" value="{{ Request::get('searchFechaH') }}">
            </div>
            <div class="form-group">
              <label for="Fecha">Raz&oacute;n Social:</label>
              <input type="text" name="searchRazonSocialH" class="form-control" value="{{ Request::get('searchRazonSocialH') }}">
            </div>
            <button type="submit" class="btn btn-default">Filtrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Historial de Liquidaciones</h3>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
          @if (count($liquidaciones) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-4">Fecha de Liquidacion</th>
                <th class="col-sm-2">Raz&oacute;n Social</th>
                <th class="col-sm-2">CUIT</th>
                <th class="col-sm-2">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($liquidaciones as $liquidacion)
              <tr>
                <td>{{ $liquidacion->created_at->format('d/m/Y') }}</td>
                <td>{{ $liquidacion->cliente->razonsocial }}</td>
                <td>{{ $liquidacion->cliente->cuit }}</td>
                <td>
                  <a class="label label-default" data-toggle="modal" data-target="#ImprimirLiquidacion{{ $liquidacion->id}}">
                    <i class="fa fa-info-circle"></i> Imprimir
                  </a>
                  <!- ModalInformes -->
                  <div class="modal fade" id="ImprimirLiquidacion{{ $liquidacion->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-body" id="imp{{ $liquidacion->id}}" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                          <table width="100%">
                            <tbody>
                              <tr>
                                <td>
                                  <p>ACUÑA HERMANOS S.A</p>
                                  <p>Alvear 731 - 2760 - San Antonio de Areco <br />
                                  Tel: 02326-454800 - Cel: 2325-651793
                                  </p>
                                </td>
                                <td align="right">
                                  <p>Liquidaci&oacute;n <br />
                                  Numero: {{ $liquidacion->id }}<br />
                                  Fecha: {{ $liquidacion->created_at->format('d/m/Y') }}
                                  </p>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2"><hr /></td>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <p>
                                  Liquidaci&oacute;n de {{ $liquidacion->cliente->razonsocial }} a favor de ACUÑA HERMANOS S.A <br />
                                  Por acarreos segun vales detallados a continuación - Hasta fecha {{ $liquidacion->created_at->format('d/m/Y') }}
                                  </p>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          
                          <table class="table table-striped" style="font-size: 14px;">
                            <thead>
                              <tr>
                                <th>Fecha</th>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>N° C.Porte</th>
                                <th>Tarifa</th>
                                <th>Kilometros</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($liquidacion->viajesLiquidados as $viajeLiquidado)
                              <tr>
                                <td>{{ $viajeLiquidado->fecha->format('d/m/Y') }}</td>
                                <td>{{ $viajeLiquidado->placeDeparture->lugar }}</td>
                                <td>{{ $viajeLiquidado->placeDestination->lugar }}</td>
                                <td>{{ $viajeLiquidado->ncporte }}</td>
                                <td>{{ $viajeLiquidado->tarifa_cliente }}</td>
                                <td>{{ $viajeLiquidado->kilometro }}</td>
                                <td>{{ $viajeLiquidado->kilo }}</td>
                                <td>${{ number_format($viajeLiquidado->importe_cliente, 2) }}</td>
                              </tr>
                              @endforeach
                              <tr>
                                <td colspan="7">
                                  <b>Total de viajes</b>
                                </td>
                                <td>${{ number_format($liquidacion->importe_viajes, 2) }}</td>
                              </tr>
                              <tr>
                                <td colspan="7">
                                  <b>TOTAL</b>
                                </td>
                                <td>
                                  <b>${{ number_format($liquidacion->importe_total, 2) }}</b>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-warning" onclick="prints({{$liquidacion->id}});">
                            <i class="fa fa-print"></i> Imprimir
                          </button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!- finModalInformes -->
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $liquidaciones->links() }}
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section("scripts")
<script >
function prints(id) {
  $('#ImprimirLiquidacion'+id).modal('hide');
  var d = document.getElementById('ImprimirLiquidacion'+id);
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

