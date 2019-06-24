@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$chofer->nombre}}</h3>
      <p class="panel-subtitle">{{$create ? 'Generar nuevo chofer' : 'Modificar datos del chofer numero '.$chofer->id}}</p>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">
          <form action="{{ $create ? route('drivers.store') : route('drivers.update', ['driver' => $chofer->id])}}" method="POST">
            {{ csrf_field() }}
            {{ method_field($create ? 'POST' : 'PUT') }}
              <div class="form-group col-md-12 col-xs-12">
                <div class="row">
                  <div class="col-md-6 col-xs-12">
                  <label class="control-label">Unidad</label>
                  </div>
                  
                 
                </div> 
                <div class="">
                  <select name="idUnidad" class="form-control text-uppercase" id="Unidad">
                    <option value="">Seleccionar unidad</option>
                    @foreach ($unidades as $unidad)
                      @if ($unidad->usado == 0 or $unidad->id == $chofer->idUnidad)
                        <option value="{{ $unidad->id }}" class="text-uppercase" @if ($chofer->idUnidad == $unidad->id) selected @endif>{{ $unidad->dominio }} ({{ $unidad->brand->detalle }} {{ $unidad->modelo }} {{ $unidad->anio }}) ({{ $unidad->owner->nombre }} {{ $unidad->owner->apellido }})</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>

              <div id="{{ $create ? 'formularioDriver' : '' }}" style="{{$create ? 'display: none;' : ''}}">
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Nombre</label>
                  <div class="">
                    <input type="text" value="{{ $chofer->nombre }}" name="nombre" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Apellido</label>
                  <div class="">
                    <input type="text" value="{{ $chofer->apellido }}" name="apellido" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">CUIT <span class="text-danger">{{$create ? '(No se puede modificar una vez creado)' : ''}} </span></label>
                  <div class="">
                    <input type="text" value="{{ $chofer->cuit }}" id="cuit" name="cuit" class="form-control" {{ $create ? '' : 'readonly' }} required>
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Nacimiento</label>
                  <div class="">
                    <input type="date" value="{{ $chofer->nacimiento }}" name="nacimiento" class="form-control">
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Direccion</label>
                  <div class="">
                    <input type="text" value="{{ $chofer->direccion }}" name="direccion" class="form-control">
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Codigo Postal</label>
                  <div class="">
                    <input type="number" value="{{ $chofer->CP }}" name="CP" class="form-control">
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Tel&eacute;fono</label>
                  <div class="">
                    <input type="number" value="{{ $chofer->tel }}" name="tel" class="form-control">
                  </div>
                </div>
              </div>
              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} chofer</button>
                <a href="{{ route('drivers.index')}}" class="btn btn-default">Cancelar</a>
                  @if ($create)
                
                    <a href="{{ route('units.create') }}?redireccion=RedirecCreateDriver" class="btn btn-primary">Agregar Unidad</a>
                  
                  @else
                  <!-- Unidad usada -->
                  <input value="{{ $chofer->idUnidad }}" name="idUnidadUsada" type="hidden" />
                  @endif
              </div>
              
          </form>
        </div>
      </div>

    </div>
  </div>

@endsection

@section("scripts")
<script src="{{ mix('js/general.form.js') }}"></script>
<script src="{{ mix('js/drivers.js') }}"></script>
@endsection
