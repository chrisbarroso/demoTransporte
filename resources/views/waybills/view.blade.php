@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista de carta de porte numero {{ $cp->ncporte}}</h3>
    </div>
    <div class="panel-body">

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Cliente: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->client->razonsocial }}</b>&nbsp;
        </div>
      </div>
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">N° carta de porte: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->ncporte }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Chofer asignado: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->driverCP->nombre }} {{ $cp->driverCP->apellido }} ({{ $cp->driverCP->cuit }})</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Unidad que manejo: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>Dominio: {{ $cp->unityCP->dominio }} - Dominio Acoplado: {{ $cp->unityCP->dominioAcoplado }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Dueño de unidad: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->ownerCP->nombre }} {{ $cp->ownerCP->apellido }} ({{ $cp->ownerCP->cuit }})</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Partida: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->placeDeparture->lugar }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Destino: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->placeDestination->lugar }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Fecha: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->fecha->format('d/m/Y') }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Kilometros: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->kilometro }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Tarifa de transporte: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->tarifa_transporte }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Tarifa del cliente: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->tarifa_cliente }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Mercancia: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->merca->nombre_merca }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Kilo: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->kilo }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Porcentaje: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cp->porcentaje }} %</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Importe de transporte: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>${{ number_format($cp->importe_transporte, 2) }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Importe de porcentaje: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>${{ number_format($cp->importe_porcentaje, 2) }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Importe de cliente: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>${{ number_format($cp->importe_cliente, 2) }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Liquidado al dueño: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>@if (!$cp->liquidado_dueno) No loquidado @else Liquidado @endif</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Liquidado al cliente: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>@if (!$cp->liquidado_cliente) No loquidado @else Liquidado @endif</b>&nbsp;
        </div>
      </div>

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('waybills.index') }}" class="btn btn-default">Volver</a>
      @if (($cp->liquidado_dueno == 0) && ($cp->liquidado_cliente == 0))
      <a href="{{ route('waybills.edit', [ 'waybill' => $cp->id ]) }}" class="btn btn-info">
        <i class="fa fa-pencil-square-o"></i> Editar
      </a>
      @else
      <button type="submit" class="btn btn-default" readonly="readonly">No se puede editar (Se encuentra liquidado)</button>
      @endif
     
      
    </div>
  </div>
@endsection
