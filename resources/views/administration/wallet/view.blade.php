@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista del cliente {{ $cliente->razonsocial}}</h3>
      <p class="panel-subtitle">Numero de cliente: {{ $cliente->id}}</p>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Raz&oacute;n social : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cliente->razonsocial }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Cuit : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cliente->cuit }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Tel&eacute;fono : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cliente->tel }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Direccion : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cliente->direccion }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Codigo Postal : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $cliente->CP }}</b>&nbsp;
        </div>
      </div>

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('clients.index') }}" class="btn btn-default">Volver</a>
      <a href="{{ route('clients.edit', [ 'client' => $cliente->id ]) }}" class="btn btn-info">
        <i class="fa fa-pencil-square-o"></i> Editar
      </a>
    </div>
  </div>
@endsection
