@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

@if(Request::get('searchCliente') != null)
@php
$saldoT = 0;
$saldoActual = 0;
@endphp

<!-- Suma de saldo -->
@if(Request::get('tipoB') == 1)
  @foreach ($clienteCC->total(Request::get('searchHasta')) as $totalt)
    @if($totalt->concepto == "Liquidación")
      @php $saldoT = $saldoT - $totalt->imp; @endphp
    @else
      @if($totalt->concepto == "Nota de credito")
        @php $saldoT = $saldoT + $totalt->imp; @endphp
      @else
        @if($totalt->concepto == "Nota de débito")
          @php $saldoT = $saldoT - $totalt->imp; @endphp
        @else
          @if($totalt->concepto == "Cobranza realizada")
            @php $saldoT = $saldoT + $totalt->imp; @endphp
          @endif
        @endif
      @endif
    @endif
  @endforeach
@else
  @foreach ($clienteCC->total(Request::get('searchHasta')) as $totalt)
    @if($totalt->concepto == "Liquidación")
      @php $saldoT = $saldoT + $totalt->imp; @endphp
    @else
      @if($totalt->concepto == "Nota de credito")
        @php $saldoT = $saldoT - $totalt->imp; @endphp
      @else
        @if($totalt->concepto == "Nota de débito")
          @php $saldoT = $saldoT + $totalt->imp; @endphp
        @else
          @if($totalt->concepto == "Cobranza realizada")
            @php $saldoT = $saldoT - $totalt->imp; @endphp
          @endif
        @endif
      @endif
    @endif
  @endforeach
@endif

@if(Request::get('tipoB') == 1)
  @foreach ($clienteCC->todos(Request::get('searchDesde'), Request::get('searchHasta')) as $todosSaldoActual)
    @if($todosSaldoActual->concepto == "Liquidación")
      @php $saldoActual = $saldoActual - $todosSaldoActual->imp; @endphp
    @else
      @if($todosSaldoActual->concepto == "Nota de credito")
        @php $saldoActual = $saldoActual + $todosSaldoActual->imp; @endphp
      @else
        @if($todosSaldoActual->concepto == "Nota de débito")
          @php $saldoActual = $saldoActual - $todosSaldoActual->imp; @endphp
        @else
          @if($todosSaldoActual->concepto == "Cobranza realizada")
            @php $saldoActual = $saldoActual + $todosSaldoActual->imp; @endphp
          @endif
        @endif
      @endif
    @endif
  @endforeach
@else
  @foreach ($clienteCC->todos(Request::get('searchDesde'), Request::get('searchHasta')) as $todosSaldoActual)
    @if($todosSaldoActual->concepto == "Liquidación")
      @php $saldoActual = $saldoActual + $todosSaldoActual->imp; @endphp
    @else
      @if($todosSaldoActual->concepto == "Nota de credito")
        @php $saldoActual = $saldoActual - $todosSaldoActual->imp; @endphp
      @else
        @if($todosSaldoActual->concepto == "Nota de débito")
          @php $saldoActual = $saldoActual + $todosSaldoActual->imp; @endphp
        @else
          @if($todosSaldoActual->concepto == "Cobranza realizada")
            @php $saldoActual = $saldoActual - $todosSaldoActual->imp; @endphp
          @endif
        @endif
      @endif
    @endif
  @endforeach
@endif

@endif

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
          <form action="{{ route('clientesCC.index') }}" method="GET" class="">
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
        <h3 class="panel-title">Cuentas corriente por cliente</h3>
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
                  <button type="buttun" class="label label-success" data-toggle="modal" data-target="#Modal{{ $cliente->id }}">Ver cuenta corriente</button>
                  <div id="Modal{{ $cliente->id }}" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <form action="{{ route('clientesCC.index') }}" method="GET" class="">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Filtro</h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="form-group col-md-6 col-xs-12">
                                <label class="control-label">Desde</label>
                                <input type="date" name="searchDesde" class="form-control" value="{{ Request::get('searchDesde') }}">
                              </div>
                              <div class="form-group col-md-6 col-xs-12">
                                <label class="control-label">Hasta</label>
                                <input type="date" name="searchHasta" class="form-control" value="{{ Request::get('searchHasta') }}">
                              </div>
                              <div class="form-group col-md-12 col-xs-12">
                                <label class="control-label">Seleccionar tipo de busqueda</label>
                                <select name="tipoB" class="form-control" required>
                                  <option value="">Seleccionar</option>
                                  <option value="1">Afectando cuenta corriente</option>
                                  <option value="2">Afectando a Acuña Hermanos S.A</option>
                                </select>
                              </div>
                              <input type="hidden" name="searchCliente" class="form-control" value="{{ $cliente->id }}"> 
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Buscar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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

@if(Request::get('searchCliente') != null)
<div class="row">
  <div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title"></h3>
        
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        <div class="col-xs-12">
          <button  class="btn btn-primary" onclick="prints({{$clienteCC->id}});">Imprimir</button>
          </div>
          <div class="col-xs-12">
            <hr />
          </div>
          
          <div id="imp{{ $clienteCC->id}}">
            <div class="col-xs-12">ACUÑA HERMANOS S.A</div>
            <div class="col-xs-12">Cuenta corriente de {{ $clienteCC->razonsocial }}</div>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>N&uacute;mero</th>
                  <th>Fecha</th>
                  <th>Concepto</th>
                  <th>Motivo</th>
                  <th>Importe</th>
                  <th>Saldo</th>
                </tr>
              </thead>
              <tbody>
                
                @if($saldoT - $saldoActual != 0)
                <tr>
                  <td colspan="5"><b>Saldo anterior hasta la fecha {{ date('d-m-Y', strtotime(Request::get('searchDesde'))) }}</b></td>
                  <td>${{ number_format($saldoT - $saldoActual, 2) }} </td>
                </tr>
                @endif
                
                @php
                $saldo = $saldoT - $saldoActual;
                @endphp

                @if(Request::get('tipoB') == 1)
                  @foreach ($clienteCC->todos(Request::get('searchDesde'), Request::get('searchHasta')) as $todo)
                  @if($todo->concepto == "Liquidación")
                  @php
                  $saldo = $saldo - $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>$-{{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif

                  @if($todo->concepto == "Nota de credito")
                  @php
                  $saldo = $saldo + $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif

                  @if($todo->concepto == "Nota de débito")
                  @php
                  $saldo = $saldo - $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>-${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif

                  @if($todo->concepto == "Cobranza realizada")
                  @php
                  $saldo = $saldo + $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>

                  @if($saldo == 0)
                  <tr class="success">
                    <td colspan="5"><b>Cuenta corriente saldada</b></td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif
                  @endif
                  @endforeach
                @else
                  @foreach ($clienteCC->todos(Request::get('searchDesde'), Request::get('searchHasta')) as $todo)
                  @if($todo->concepto == "Liquidación")
                  @php
                  $saldo = $saldo + $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif

                  @if($todo->concepto == "Nota de credito")
                  @php
                  $saldo = $saldo - $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>-${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif

                  @if($todo->concepto == "Nota de débito")
                  @php
                  $saldo = $saldo + $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif

                  @if($todo->concepto == "Cobranza realizada")
                  @php
                  $saldo = $saldo - $todo->imp;
                  @endphp
                  <tr>
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td>{{ $todo->concepto }}</td>
                    <td>{{ $todo->motivo }}</td>
                    <td>-${{ number_format($todo->imp, 2) }}</td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>

                  @if($saldo == 0)
                  <tr class="success">
                    <td colspan="5"><b>Cuenta corriente saldada</b></td>
                    <td>${{ number_format($saldo, 2) }}</td>
                  </tr>
                  @endif
                  @endif
                  @endforeach
                @endif

                <tr class="success">
                  @if(Request::get('searchHasta'))
                  <td colspan="5"><b>Saldo a la fecha {{ date('d-m-Y', strtotime(Request::get('searchHasta'))) }} </b></td>
                  @else
                  <td colspan="5"><b>Saldo a la fecha {{ date('d-m-Y') }}</b></td>
                  @endif
                  <td>${{ number_format($saldo, 2) }}</td>
                </tr>
                
              </tbody>
            </table>
          </div>
          
          
        </div>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@section("scripts")
<script>
function prints(id) {
  var contenido = document.getElementById('imp'+id).innerHTML;
  var contenidoOriginal = document.body.innerHTML;
  document.body.innerHTML = contenido;
  
  window.print();
  document.body.innerHTML = contenidoOriginal;
};
</script>
@endsection

