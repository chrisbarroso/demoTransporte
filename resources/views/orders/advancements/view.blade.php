@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista del adelanto {{ $adelanto->id}}</h3>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Chofer : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $adelanto->driverAde->nombre }} {{ $adelanto->driverAde->apellido }}</b>&nbsp;
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Unidad : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $adelanto->unityAde->dominio }}</b>&nbsp;
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Dueno : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $adelanto->ownerAde->nombre }} {{ $adelanto->ownerAde->apellido }}</b>&nbsp;
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Fecha : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $adelanto->fecha->format('d/m/Y') }}</b>&nbsp;
        </div>
    </div>

    <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Importe total: </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>${{ number_format($adelanto->importe_total, 2) }}</b>&nbsp;
        </div>
    </div>


    <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Liquidado : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
          @if($adelanto->liquidado)
            Si
          @else
            No
          @endif
          </b>&nbsp;
        </div>
    </div>

    @foreach ($pagosSalida as $pagoSalida)
    <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Entregado con : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
            @if($pagoSalida->forma_pago == "Efectivo")
              Efectivo - ${{ number_format($pagoSalida->importe_efectivo, 2) }}
            @endif
            
            @if($pagoSalida->id_forma_pago == 2)
              {{ $pagoSalida->formPaymentOut->detalle }} - ${{ number_format($pagoSalida->importeF, 2) }} - Numero de cheque: {{ $pagoSalida->nro_cheque }}
            @endif

            @if($pagoSalida->id_forma_pago == 3)
              {{ $pagoSalida->formPaymentOut->detalle }} - ${{ number_format($pagoSalida->walletOut->importeF, 2) }} - Numero de cheque: {{ $pagoSalida->walletOut->nro_cheque }}
            @endif
          </b>&nbsp;
        </div>
    </div>

    

   
      
    @endforeach

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('advancements.index') }}" class="btn btn-default">Volver</a>
      @if($adelanto->liquidado)
        <a href="#" class="btn btn-default" disabled="true">
          <i class="fa fa-pencil-square-o"></i> Editar
        </a>
      @else
        <a href="{{ route('advancements.edit', [ 'advancement' => $adelanto->id ]) }}" class="btn btn-info">
          <i class="fa fa-pencil-square-o"></i> Editar
        </a>
      @endif
      
    </div>
  </div>
@endsection
