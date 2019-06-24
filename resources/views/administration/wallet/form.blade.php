@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">{{ $create ? 'Crear' : 'Editar '.$cheque->nro_cheque }}</h3>
      <p class="panel-subtitle">{{ $create ? 'Generar nuevo cheque' : 'Modificar datos del cheque' }}</p>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">
          <form action="{{ $create ? route('wallet.store') : route('wallet.update', ['wallet' => $cheque->id])}}" method="POST">
            {{ csrf_field() }}
            {{ method_field($create ? 'POST' : 'PUT') }}
              <div class="form-group col-xs-12 col-md-4">
                <label class="control-label">Numero de cheque</label>
                <div class="">
                  <input type="number" value="{{$cheque->nro_cheque}}" name="nro_cheque" class="form-control" required>
                </div>
              </div>

              <div class="form-group col-xs-12 col-md-4">
                <label class="control-label">Importe</label>
                <div class="">
                  <input type="number" name="importeF" value="{{ $cheque->importeF }}" class="form-control" step="any" required>
                </div>
              </div>

              <div class="form-group col-xs-12 col-md-4">
                <label class="control-label">Banco</label>
                <div class="">
                  <select name="id_banco" class="form-control" require>
                    <option value="" selected>Seleccionar banco</option>
                    @foreach($bancos as $banco)
                    <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group col-xs-12 col-md-12">
                <label class="control-label">Obervacion</label>
                <div class="">
                  <textarea name="observacion" class="form-control" required>{{ $cheque->observacion }}</textarea>
                </div>
              </div>

              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} cheque</button>
                <a href="{{ route('wallet.index')}}" class="btn btn-default">Cancelar</a>
                
              </div>
          </form>
        </div>
      </div>

    </div>
  </div>

@endsection

@section("scripts")

@endsection
