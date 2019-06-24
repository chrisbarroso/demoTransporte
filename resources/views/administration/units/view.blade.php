@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista de la unidad</h3>
      <p class="panel-subtitle">Numero de cliente: {{ $unidad->id}}</p>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Dueño : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>
          @if (isset($unidad->owner->id)) 
            {{ $unidad->owner->nombre }} {{ $unidad->owner->apellido }} ({{ $unidad->owner->cuit }})
          @else
            No cargado
          @endif
          </b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Dominio : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b class="text-uppercase">{{ $unidad->dominio }}</b>
        </div>
      </div>
  
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Marca : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $unidad->brand->detalle ? $unidad->brand->detalle : 'No cargado' }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Modelo : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $unidad->modelo ? $unidad->modelo : 'No cargado' }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Año : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $unidad->anio ? $unidad->anio : 'No cargado' }}</b>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Dominio acoplado : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          @if (isset($unidad->dominioAcoplado)) 
            <b class="text-uppercase">{{ $unidad->dominioAcoplado }}</b>
          @else
            <b>No cargado</b>
          @endif
        </div>
      </div>

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('units.index') }}" class="btn btn-default">Volver</a>
      <a href="{{ route('units.edit', [ 'unity' => $unidad->id ]) }}" class="btn btn-info">
        <i class="fa fa-pencil-square-o"></i> Editar
      </a>
    </div>
  </div>
@endsection
