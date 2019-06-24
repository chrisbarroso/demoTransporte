@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista del chofer {{ $chofer->nombre}} {{ $chofer->apellido}}</h3>
      <p class="panel-subtitle">Numero de chofer: {{ $chofer->id}}</p>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Unidad : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
          @if (isset($chofer->unity->dominio)) 
            (Dominio <span class="text-uppercase">{{ $chofer->unity->dominio }}</span>) (Marca {{ $chofer->unity->brand->detalle }}) (Dueño {{ $chofer->unity->owner->nombre }} {{ $chofer->unity->owner->apellido }} {{ $chofer->unity->owner->cuit }})  
          @else
            No cargado
          @endif
          </b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Nombre : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->nombre }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Apellido : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->apellido }}</b>
        </div>
      </div>
    
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Cuit : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->cuit }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Nacimiento : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->nacimiento ? $chofer->nacimiento->format('d/m/Y') : 'No cargado' }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Direccion : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->direccion ? $chofer->direccion : 'No cargado' }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Codigo Postal : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->CP ? $chofer->CP : 'No cargado' }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Tel&eacute;fono : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $chofer->tel ? $chofer->tel : 'No cargado' }}</b>
        </div>
      </div>

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('drivers.index') }}" class="btn btn-default">Volver</a>
      <a href="{{ route('drivers.edit', [ 'driver' => $chofer->id ]) }}" class="btn btn-info">
        <i class="fa fa-pencil-square-o"></i> Editar
      </a>
    </div>
  </div>
@endsection
