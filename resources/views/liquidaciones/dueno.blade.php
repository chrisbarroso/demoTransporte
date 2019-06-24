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
          <form action="{{ route('dueno.index') }}" method="GET" class="">
              {{ csrf_field() }}
            <div class="form-group">
              <label for="Nombre">Nombre:</label>
              <input type="text" name="searchNombre" class="form-control" value="{{ Request::get('searchNombre') }}">
            </div>
            <div class="form-group">
              <label for="Nombre">Apellido:</label>
              <input type="text" name="searchApellido" class="form-control" value="{{ Request::get('searchApellido') }}">
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
        <h3 class="panel-title">Liquidacion por dueño</h3>
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
                <th class="col-sm-3">CUIT</th>
                <th class="col-sm-3">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($duenos as $dueno)
              <tr>
                <td>{{ $dueno->nombre }}</td>
                <td>{{ $dueno->apellido }}</td>
                <td>{{ $dueno->cuit }}</td>
                <td>
                  <a class="label label-success" data-toggle="modal" data-target="#Viajes{{ $dueno->id}}">
                    <i class="fa fa-info-circle"></i> Liquidar
                  </a>
                  @php
                  ${"tiene-" . $dueno->id} = 0;
                  ${"subtotalViajes-" . $dueno->id} = 0;
                  ${"subtotalComision-" . $dueno->id} = 0;
                  ${"total-" . $dueno->id} = 0;
                  $ultimodominio = "";
                  @endphp
                  <!- ModalInformes -->
                  <div class="modal fade" id="Viajes{{ $dueno->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                  <p>Liquidaci&oacute;n a favor de {{ $dueno->apellido }} {{ $dueno->nombre }}<br />
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
                                <th>Cliente</th>
                                <th>Tarifa</th>
                                <th>Kilometros</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                                <th>Comisión</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($dueno->viajesLiquidadar as $viaje)
                              @php
                              ${"tiene-" . $dueno->id} = 1;
                              ${"subtotalViajes-" . $dueno->id} =  ${"subtotalViajes-" . $dueno->id} + $viaje->importe_transporte;
                              ${"subtotalComision-" . $dueno->id} =  ${"subtotalComision-" . $dueno->id} + $viaje->importe_porcentaje;
                              @endphp
                              @if($ultimodominio != $viaje->unityCP->dominio)
                              <tr align="center">
                                <td colspan="10">Camion - Dominio {{ $viaje->unityCP->dominio }}</td>
                              </tr>
                              @php
                              $ultimodominio = $viaje->unityCP->dominio;
                              @endphp
                              @endif
                              <tr>
                                <td>{{ $viaje->fecha->format('d/m/Y') }}</td>
                                <td>{{ $viaje->placeDeparture->lugar }}</td>
                                <td>{{ $viaje->placeDestination->lugar }}</td>
                                <td>{{ $viaje->ncporte }}</td>
                                <td>{{ $viaje->client->razonsocial }}</td>
                                <td>{{ $viaje->tarifa_transporte }}</td>
                                <td>{{ $viaje->kilometro }}</td>
                                <td>{{ $viaje->kilo }}</td>
                                <td>${{ number_format($viaje->importe_transporte, 2) }}</td>
                                <td>$-{{ number_format($viaje->importe_porcentaje, 2) }} (%{{ $viaje->porcentaje }})</td>
                              </tr>
                              @endforeach
                              <tr>
                                <td colspan="8">
                                SUBTOTAL
                                </td>
                                <td>
                                  ${{ number_format(${"subtotalViajes-" . $dueno->id}, 2) }}
                                </td>
                                <td>$-{{ number_format(${"subtotalComision-" . $dueno->id}, 2) }}</td>
                              </tr>
                              <tr>
                                <td colspan="9">
                                  <b>TOTAL</b>
                                </td>
                                <td>
                                  @php
                                  ${"total-" . $dueno->id} = ${"subtotalViajes-" . $dueno->id} - ${"subtotalComision-" . $dueno->id};
                                  @endphp
                                  <b>${{ number_format(${"total-" . $dueno->id}, 2) }}</b>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          @if(${"tiene-" . $dueno->id})
                          <form action="{{ route('dueno.guardado', ['id_dueno' => $dueno->id])}}" method="GET">
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
          {{ $duenos->links() }}
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
          <form action="{{ route('dueno.index') }}" method="GET" class="">
              {{ csrf_field() }}
            <div class="form-group">
              <label for="Nombre">Fecha:</label>
              <input type="date" name="searchFechaH" class="form-control" value="{{ Request::get('searchFechaH') }}">
            </div>
            <div class="form-group">
              <label for="Nombre">Nombre:</label>
              <input type="text" name="searchNombreH" class="form-control" value="{{ Request::get('searchNombreH') }}">
            </div>
            <div class="form-group">
              <label for="Nombre">Apellido:</label>
              <input type="text" name="searchApellidoH" class="form-control" value="{{ Request::get('searchApellidoH') }}">
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
                <th class="col-sm-4">Fecha de Liquidaci&oacute;n</th>
                <th class="col-sm-2">Nombre</th>
                <th class="col-sm-2">Apellido</th>
                <th class="col-sm-2">CUIT</th>
                <th class="col-sm-2">Importe liquidaci&oacute;n</th>
                <th class="col-sm-2">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($liquidaciones as $liquidacion)
              <tr>
                <td>{{ $liquidacion->created_at->format('d/m/Y') }}</td>
                <td>{{ $liquidacion->dueno->nombre }}</td>
                <td>{{ $liquidacion->dueno->apellido }}</td>
                <td>{{ $liquidacion->dueno->cuit }}</td>
                <td>${{ number_format($liquidacion->importe_total, 2) }}</td>
                <td>
                 
                  <a class="label label-default" data-toggle="modal" data-target="#ImprimirLiquidacion{{ $liquidacion->id}}">
                    <i class="fa fa-info-circle"></i> Imprimir
                  </a>
                  @php
                  $ultimodominioImpr = "";
                  @endphp
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
                                <p>Liquidaci&oacute;n<br />
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
                                <p>Liquidaci&oacute;n a favor de {{ $liquidacion->dueno->apellido }} {{ $liquidacion->dueno->nombre }}<br />
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
                                <th>Cliente</th>
                                <th>Tarifa</th>
                                <th>Kilometros</th>
                                <th>Kilos</th>
                                <th>Importe</th>
                                <th>Comisión</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($liquidacion->viajesLiquidados as $viajeLiquidado)
                              @if($ultimodominioImpr != $viajeLiquidado->unityCP->dominio)
                              <tr align="center">
                                <td colspan="10">Camion - Dominio {{ $viajeLiquidado->unityCP->dominio }}</td>
                              </tr>
                              @php
                              $ultimodominioImpr = $viajeLiquidado->unityCP->dominio;
                              @endphp
                              @endif
                              <tr>
                                <td>{{ $viajeLiquidado->fecha->format('d/m/Y') }}</td>
                                <td>{{ $viajeLiquidado->placeDeparture->lugar }}</td>
                                <td>{{ $viajeLiquidado->placeDestination->lugar }}</td>
                                <td>{{ $viajeLiquidado->ncporte }}</td>
                                <td>{{ $viajeLiquidado->client->razonsocial }}</td>
                                <td>{{ $viajeLiquidado->tarifa_transporte }}</td>
                                <td>{{ $viajeLiquidado->kilometro }}</td>
                                <td>{{ $viajeLiquidado->kilo }}</td>
                                <td>${{ number_format($viajeLiquidado->importe_transporte, 2) }}</td>
                                <td>$-{{ number_format($viajeLiquidado->importe_porcentaje, 2) }} (%{{ $viajeLiquidado->porcentaje }})</td>
                              </tr>
                              @endforeach
                              <tr>
                                <td colspan="8">
                                SUBTOTAL
                                </td>
                                <td>
                                  ${{ number_format($liquidacion->importe_viajes, 2) }}
                                </td>
                                <td>$-{{ number_format($liquidacion->importe_comision, 2) }}</td>
                              </tr>
                              <tr>
                                <td colspan="9">
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

