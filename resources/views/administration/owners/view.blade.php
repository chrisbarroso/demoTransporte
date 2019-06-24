@extends('layouts.app') @section('content')
<div class="panel panel-headline">
  <div class="panel-heading">
    <h3 class="panel-title">Vista del dueño {{ $dueno->nombre}}</h3>
    <p class="panel-subtitle">Numero del dueño: {{ $dueno->id}}</p>
  </div>
  <div class="panel-body">

    <div class="col-xs-12">
      <div class="col-md-2 col-xs-12">
        <label class="control-label">Nombre : </label>
      </div>
      <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $dueno->nombre }}</b>&nbsp;
      </div>
    </div>

    <div class="col-xs-12">
      <div class="col-md-2 col-xs-12">
        <label class="control-label">Apellido : </label>
      </div>
      <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $dueno->apellido }}</b>&nbsp;
      </div>
    </div>

    <div class="col-xs-12">
      <div class="col-md-2 col-xs-12">
        <label class="control-label">CUIT : </label>
      </div>
      <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $dueno->cuit }}</b>&nbsp;
      </div>
    </div>

    <div class="col-xs-12">
      <div class="col-md-2 col-xs-12">
        <label class="control-label">Nacimiento : </label>
      </div>
      <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $dueno->nacimiento ? $dueno->nacimiento->format('d/m/Y') : 'No cargado' }}</b>&nbsp;
      </div>
    </div>

    <div class="col-xs-12">
      <div class="col-md-2 col-xs-12">
        <label class="control-label">Direccion : </label>
      </div>
      <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $dueno->direccion ? $dueno->direccion : 'No cargado' }}</b>&nbsp;
      </div>
    </div>

    <div class="col-xs-12">
      <div class="col-md-2 col-xs-12">
        <label class="control-label">Codigo Postal : </label>
      </div>
      <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
        <b>{{ $dueno->CP ? $dueno->CP : 'No cargado' }}</b>&nbsp;
      </div>
    </div>

  </div>

  <div class="panel-footer text-right">
    <a href="{{ route('owners.index') }}" class="btn btn-default">Volver</a>
    <a href="{{ route('owners.edit', [ 'owner' => $dueno->id ]) }}" class="btn btn-info">
      <i class="fa fa-pencil-square-o"></i> Editar
    </a>
  </div>
</div>
@endsection