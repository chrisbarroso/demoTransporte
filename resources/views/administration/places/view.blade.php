@extends('layouts.app')

@section('content')
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">Vista del lugar {{ $lugar->lugar}}</h3>
      <p class="panel-subtitle">Numero del lugar: {{ $lugar->id}}</p>
    </div>
    <div class="panel-body">
      
      <div class="col-xs-12">
        <div class="col-md-2 col-xs-12">
          <label class="control-label">Lugar : </label>
        </div>
        <div class="col-md-10 col-xs-12" style="background: #cccccc;color: #2b333e;">
          <b>{{ $lugar->lugar }}</b>&nbsp;
        </div>
      </div>
    </div>
  
    <div class="panel-footer text-right">
      <a href="{{ route('places.index') }}" class="btn btn-default">Volver</a>
      <a href="{{ route('places.edit', [ 'place' => $lugar->id ]) }}" class="btn btn-info">
        <i class="fa fa-pencil-square-o"></i> Editar
      </a>
    </div>
  </div>
@endsection
