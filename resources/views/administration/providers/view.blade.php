@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista del proveedor {{ $proveedor->razonsocial}}</h3>
      <p class="panel-subtitle">Numero de proveedor: {{ $proveedor->id}}</p>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Raz&oacute;n social : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $proveedor->razonsocial }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Cuit : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $proveedor->cuit }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Tel&eacute;fono : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $proveedor->tel }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Direccion : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $proveedor->direccion }}</b>&nbsp;
        </div>
      </div>

      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Codigo Postal : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $proveedor->CP }}</b>&nbsp;
        </div>
      </div>

    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('providers.index') }}" class="btn btn-default">Volver</a>
      <a href="{{ route('providers.edit', [ 'provider' => $proveedor->id ]) }}" class="btn btn-info">
        <i class="fa fa-pencil-square-o"></i> Editar
      </a>
    </div>
  </div>
@endsection
