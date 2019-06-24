@extends('layouts.app') @section('content') 
<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="col-md-12 col-xs-12">
  <div class="panel panel-headline">
    <div class="panel-heading">
      <h3 class="panel-title">{{$create ? 'Crear' : 'Editar '.$compra->id}}</h3>
      <p class="panel-subtitle">{{$create ? 'Generar nueva orden de compra' : 'Modificar datos de la orden de compra numero '.$compra->id}}</p>
      @if(!$create)
      <p>Chofer: {{ $compra->driverPur->nombre }} {{ $compra->driverPur->apellido }} ({{ $compra->driverPur->cuit }})<br />
        Unidad -> Dominio: {{ $compra->unityPur->dominio }} - Dominio Acoplado: {{ $compra->unityPur->dominioAcoplado }}<br />
        Dueño de la unidad: {{ $compra->ownerPur->nombre }} {{ $compra->ownerPur->apellido }} ({{ $compra->ownerPur->cuit }})
      </p>
      @endif
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">

          <form action="{{ $create ? route('purchases.store') : route('purchases.update', ['purchase' => $compra->id])}}" method="POST">
            {{ csrf_field() }} {{ method_field($create ? 'POST' : 'PUT') }}

            @if($create)
            <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Chofer / Unidad / Dueño</label>
                <div class="">
                    <select name="id_chofer" class="form-control" required>
                        <option value="">Seleccionar chofer</option>
                        @foreach ($choferes as $chofer)
                          @if($chofer->idUnidad != null)
                            <option value="{{ $chofer->id }}" @if ($compra->id_chofer == $chofer->id) selected @endif>{{ $chofer->nombre }} {{ $chofer->apellido }} ({{ $chofer->cuit }}) / {{ $chofer->unity->dominio }} / {{ $chofer->unity->owner->nombre }} {{ $chofer->unity->owner->apellido }} ({{ $chofer->unity->owner->cuit }})</option>
                          @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Proveedor</label>
                <div class="">
                    <select name="id_proveedor" class="form-control" required>
                        <option value="">Seleccionar proveedor</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" @if ($compra->id_proveedor == $proveedor->id) selected @endif>{{ $proveedor->razonsocial }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Fecha</label>
                <div class="">
                  @if($create)
                    <input type="date" name="fecha" class="form-control" required />
                  @else
                    <input type="date" name="fecha" value="{{ $compra->fecha->format('Y-m-d') }}" class="form-control" required />
                  @endif
                </div>
            </div>

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Numero de control</label>
                <div class="">
                    <input type="number" name="nro_control" value="{{ $compra->nro_control }}" class="form-control" required/>
                </div>
            </div>

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Tanque lleno</label>
                <div class="">
                  @if($create)
                    <input type="checkbox" name="tanque_lleno" class="form-control" value="1" />
                  @else
                    <input type="checkbox" name="tanque_lleno" class="form-control" value="1" @if($compra->tanque_lleno) checked @endif />
                  @endif
                </div>
            </div>

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Litros</label>
                <div class="">
                    <input type="number" name="litros" value="{{ $compra->litros }}" class="form-control" />
                </div>
            </div>

            <div class="form-group col-md-6 col-xs-12">
                <label class="control-label">Importe</label>
                <div class="">
                    <input type="number" name="importe" step="any" value="{{ $compra->importe }}" class="form-control" />
                </div>
            </div>
            
            <div class="form-group col-md-12 col-xs-12">
              <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} orden de compra</button>
              <a href="{{ route('purchases.index') }}" class="btn btn-default">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section("scripts")
<script src="{{ mix('js/general.form.js') }}"></script>
@endsection