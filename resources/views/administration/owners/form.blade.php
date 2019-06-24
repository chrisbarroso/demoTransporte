@extends('layouts.app') @section('content') 
<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="col-md-12 col-xs-12">
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$dueno->cuit}}</h3>
      <p class="panel-subtitle">{{$create ? 'Generar nuevo dueño' : 'Modificar datos del dueño numero '.$dueno->id}}</p>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">

          <form action="{{ $create ? route('owners.store') : route('owners.update', ['owner' => $dueno->id])}}" method="POST">
            {{ csrf_field() }} {{ method_field($create ? 'POST' : 'PUT') }}

            <div class="form-group col-md-6 col-xs-12">
              <label class="control-label">Nombre</label>
              <div class="">
                <input type="text" name="nombre" class="form-control" value="{{ $dueno->nombre }}" required>
              </div>
            </div>
            <div class="form-group col-md-6 col-xs-12">
              <label class="control-label">Apellido</label>
              <div class="">
                <input type="text" name="apellido" class="form-control" value="{{ $dueno->apellido }}" required>
              </div>
            </div>
            <div class="form-group col-md-12 col-xs-12">
              <label class="control-label">CUIT
                <span class="text-danger">{{ $create ? '(No se puede modificar una vez creado)' : '' }}</span>
              </label>
              <div class="">
                <input type="text" id="cuit" name="cuit" class="form-control" value="{{ $dueno->cuit }}" {{$create ? '' : 'readonly'}} required>
              </div>
            </div>
            <div class="form-group col-md-6 col-xs-12">
              <label class="control-label">Nacimiento</label>
              <div class="">
                <input type="date" name="nacimiento" class="form-control" value="{{ $dueno->nacimiento }}">
              </div>
            </div>
            <div class="form-group col-md-6 col-xs-12">
              <label class="control-label">Direccion</label>
              <div class="">
                <input type="text" name="direccion" class="form-control" value="{{ $dueno->direccion }}">
              </div>
            </div>
            <div class="form-group col-md-6 col-xs-12">
              <label class="control-label">Codigo Postal</label>
              <div class="">
                <input type="number" name="CP" class="form-control" value="{{ $dueno->CP }}">
              </div>
            </div>
            <div class="form-group col-md-6 col-xs-12">
              <label class="control-label">Tel&eacute;fono</label>
              <div class="">
                <input type="number" name="tel" class="form-control" value="{{ $dueno->tel }}">
              </div>
            </div>
            <div class="form-group col-md-12 col-xs-12">
              <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} dueño</button>
              <a href="{{ route('owners.index') }}" class="btn btn-default">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection @section("scripts")
<script src="{{ mix('js/general.form.js') }}"></script> @endsection