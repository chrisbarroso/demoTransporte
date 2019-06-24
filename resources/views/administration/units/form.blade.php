@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

    <div class="{{Request::get('redireccion') ? 'col-md-6' : 'col-md-12'}} col-xs-12">
    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$unidad->nombre}}</h3>
        <p class="panel-subtitle">{{$create ? 'Generar nueva unidad' : 'Modificar datos de la unidad numero '.$unidad->id}}</p>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <form action="{{ $create ? route('units.store') : route('units.update', ['unity' => $unidad->id])}}" method="POST">
              {{ csrf_field() }}
              {{ method_field($create ? 'POST' : 'PUT') }}
              <input type="hidden" name="casos" value="unidad" class="form-control" />
              <input type="hidden" name="redireccion" value="{{ Request::get('redireccion') }}" class="form-control" />
              <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Dueño</label>
                <div class="">
                  <select name="idDueno" class="form-control" required>
                    <option value="">Seleccionar dueño</option>
                    @foreach ($duenosUnidades as $duenoUnidad)
                    <option value="{{ $duenoUnidad->id }}" @if ($unidad->idDueno == $duenoUnidad->id) selected @endif>{{ $duenoUnidad->nombre }} {{ $duenoUnidad->apellido }} ({{ $duenoUnidad->cuit }})</option>
                    @endforeach
                  </select>

                  @if (!$create)
                    <input type="hidden" name="idDuenoAnterior" value="{{$unidad->idDueno}}" />
                  @endif
                </div>
              </div>
              <div class="form-group col-md-12 col-xs-12">
              <label class="control-label">Dominio <span class="text-danger">{{ $create ? '(No se puede modificar una vez creado)' : '' }}</span></label>
                <div class="">
                  <input type="text" value="{{ $unidad->dominio }}" name="dominio" class="form-control text-uppercase" {{$create ? '' : 'readonly'}} required>
                </div>
              </div>
              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Marca</label>
                <div class="">
                  <select name="idMarca" class="form-control">
                    <option value="">Seleccionar marca</option>
                    @foreach ($marcas as $marca)
                    <option value="{{ $marca->id }}" @if ($unidad->idMarca == $marca->id) selected @endif>{{ $marca->detalle }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Modelo</label>
                <div class="">
                  <input type="number" name="modelo" value="{{ $unidad->modelo }}" class="form-control" />
                </div>
              </div>
              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Año</label>
                <div class="">
                  <input type="number" name="anio" min="1800" max="2099" step="1" value="{{ $unidad->anio }}" class="form-control" />
                </div>
              </div>
              <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Dominio del acoplado</label>
                <div class="">
                  <input type="text" value="{{ $unidad->dominioAcoplado }}" name="dominioAcoplado" class="form-control text-uppercase">
                </div>
              </div>
              
              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} unidad</button>
                <a href="{{Request::get('redireccion') ? route('drivers.create') : route('units.index')}}" class="btn btn-default">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (Request::get('redireccion'))
  <div class="col-md-6 col-xs-12">
    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">Dueños</h3>
        <p class="panel-subtitle">Crear un nuevo dueño</p>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <form action="{{ route('units.store') }}" method="POST">
              {{ csrf_field() }}
              {{ method_field('POST') }}
              <input type="hidden" name="casos" value="dueno" class="form-control" />
              <input type="hidden" name="redireccion" value="{{ Request::get('redireccion') }}" class="form-control" />
              <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Nombre</label>
                  <div class="">
                    <input type="text" name="nombre" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Apellido</label>
                  <div class="">
                    <input type="text" name="apellido" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">CUIT <span class="text-danger">{{ $create ? '(No se puede modificar una vez creado)' : '' }}</span></label>
                  <div class="">
                    <input type="text" id="cuit" name="cuit" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Nacimiento</label>
                  <div class="">
                    <input type="date" name="nacimiento" class="form-control">
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Direccion</label>
                  <div class="">
                    <input type="text" name="direccion" class="form-control">
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Codigo Postal</label>
                  <div class="">
                    <input type="number" name="CP" class="form-control">
                  </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                  <label class="control-label">Tel&eacute;fono</label>
                  <div class="">
                    <input type="number" name="tel" class="form-control">
                  </div>
                </div>
              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">Crear dueño</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

@endsection

@section("scripts")
<script src="{{ mix('js/general.form.js') }}"></script>
@endsection
