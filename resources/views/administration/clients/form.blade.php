@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$cliente->razonsocial}}</h3>
      <p class="panel-subtitle">{{$create ? 'Generar nuevo cliente' : 'Modificar datos del cliente numero '.$cliente->id}}</p>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">
          <form action="{{ $create ? route('clients.store') : route('clients.update', ['client' => $cliente->id])}}" method="POST">
            {{ csrf_field() }}
            {{ method_field($create ? 'POST' : 'PUT') }}
              <div class="form-group col-xs-12 col-md-12">
                <label class="control-label">Raz&oacute;n social</label>
                <div class="">
                  <input type="text" value="{{$cliente->razonsocial}}" name="razonsocial" class="form-control" required>
                </div>
              </div>
              <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">CUIT <span class="text-danger">{{$create ? '(No se puede modificar una vez creado)' : ''}} </span></label>
                <div class="">
                  <input type="text" value="{{ $cliente->cuit}}" id="cuit" name="cuit" class="form-control" {{$create ? '' : 'readonly'}} required>
                </div>
              </div>
              <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Tel&eacute;fono</label>
                <div class="">
                  <input type="number" value="{{ $cliente->tel}}" name="tel" class="form-control">
                </div>
              </div>
              <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Direccion</label>
                <div class="">
                  <input type="text" value="{{ $cliente->direccion}}" name="direccion" class="form-control">
                </div>
              </div>
              <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Codigo Postal</label>
                <div class="">
                  <input type="number" value="{{ $cliente->CP}}" name="CP" class="form-control">
                </div>
              </div>
              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} cliente</button>
                <a href="{{ route('clients.index')}}" class="btn btn-default">Cancelar</a>
                
              </div>
          </form>
        </div>
      </div>

    </div>
  </div>

@endsection

@section("scripts")
<script src="{{ mix('js/general.form.js') }}"></script>
@endsection
