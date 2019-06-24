@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista de la orden de compra numero {{ $compra->id}}</h3>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Pedido por (chofer) : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $compra->driverPur->nombre }} {{ $compra->driverPur->apellido }} - ({{ $compra->driverPur->cuit }})</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Se liquida (Unidad) : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>Dominio: {{ $compra->unityPur->dominio }} - Dominio acoplado: {{ $compra->unityPur->dominioAcoplado }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Unidad pertenece (Dueño) : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $compra->ownerPur->nombre }} {{ $compra->ownerPur->apellido }} - ({{ $compra->ownerPur->cuit }})</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Proveedor : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $compra->providerPur->razonsocial }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Fecha : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $compra->fecha->format('d/m/Y') }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Tanque lleno : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
          @if($compra->tanque_lleno)
            Si
          @else
            No
          @endif
          </b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Litros : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $compra->litros }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Importe : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>${{ number_format($compra->importe, 2) }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Confirmado : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
          @if($compra->confirmado)
            Si
          @else
            No
          @endif
          </b>&nbsp;
        </div>
      </div>


      <div class="col-xs-12">
        <div class="col-md-3 col-xs-12">
          <label class="control-label">Liquidado : </label>
        </div>
        <div class="col-md-9 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
          @if($compra->liquidado)
            Si
          @else
            No
          @endif
          </b>&nbsp;
        </div>
      </div>

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('purchases.index') }}" class="btn btn-default">Volver</a>
      @if($compra->liquidado)
        <a href="#" class="btn btn-default" disabled="true">
          <i class="fa fa-pencil-square-o"></i> Editar
        </a>
      @else
        <a href="{{ route('purchases.edit', [ 'purchase' => $compra->id ]) }}" class="btn btn-info">
          <i class="fa fa-pencil-square-o"></i> Editar
        </a>
      @endif
      
    </div>
  </div>
@endsection
