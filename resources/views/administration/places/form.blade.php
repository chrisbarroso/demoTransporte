@extends('layouts.app')

@section('content')
<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
    <div class="col-md-12 col-xs-12">
    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$lugar->lugar}}</h3>
        <p class="panel-subtitle">{{$create ? 'Generar nuevo lugar' : 'Modificar datos del lugar numero '.$lugar->id}}</p>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <form action="{{ $create ? route('places.store') : route('places.update', ['place' => $lugar->id])}}" method="POST">
              {{ csrf_field() }}
              {{ method_field($create ? 'POST' : 'PUT') }}
              
              <div class="form-group col-md-12 col-xs-12">
              <label class="control-label">Lugar</label>
                <div class="">
                  <input type="text" value="{{ $lugar->lugar }}" name="lugar" class="form-control" required>
                </div>
              </div>
              
              
              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} lugar</button>
                <a href="{{ route('places.index') }}" class="btn btn-default">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> 
@endsection

@section("scripts")

@endsection
