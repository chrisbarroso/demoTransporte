@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

<div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Carta de porte</h3>
        @if(!$create)
        <p>Chofer: {{ $CP->driverCP->nombre }} {{ $CP->driverCP->apellido }} ({{ $CP->driverCP->cuit }})<br />
        Unidad -> Dominio: {{ $CP->unityCP->dominio }} - Dominio Acoplado: {{ $CP->unityCP->dominioAcoplado }}<br />
        Dueño de la unidad: {{ $CP->ownerCP->nombre }} {{ $CP->ownerCP->apellido }} ({{ $CP->ownerCP->cuit }})</p>
        @endif
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
            <form action="{{ $create ? route('waybills.store') : route('waybills.update', ['waybill' => $CP->id])}}" method="POST">
              {{ csrf_field() }}
              {{ method_field($create ? 'POST' : 'PUT') }}

              
              @if ($create)
                <input type="hidden" name="cantidadCP" value="{{Request::get('cantidadCP')}}" >
                <input type="hidden" value="{{Request::get('cliente')}}" name="idCliente">
              @else
                <input type="hidden" name="cantidadCP" value="{{$cantidadCP}}" >
                <input type="hidden" name="idCliente" value="{{$CP->idCliente}}" >
              @endif




              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Partida</label>
                <div class="">
                  <select name="idLugarPartida" class="form-control" required>
                    <option value="">Seleccionar Partida</option>
                    @foreach ($lugares as $lugar)
                    <option value="{{ $lugar->id }}"
                        @if ($CP->idLugarPartida == $lugar->id)
                          selected
                        @endif
                      >{{ $lugar->lugar }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Destino</label>
                <div class="">
                  <select name="idLugarDestino" class="form-control" required>
                    <option value="">Seleccionar Destino</option>
                    @foreach ($lugares as $lugar)
                    <option value="{{ $lugar->id }}"
                        @if ($CP->idLugarDestino == $lugar->id)
                          selected
                        @endif
                      >{{ $lugar->lugar }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>


              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Fecha</label>
                <div class="">
                  <input type="date" @if (!$create) value="{{$CP->fecha->format('Y-m-d')}}" @endif name="fecha" class="form-control" required>
                </div>
              </div>
              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Kilometros</label>
                <div class="">
                  <input type="number" value="{{ $CP->kilometro }}" name="kilometro" class="form-control" required>
                </div>
              </div>
              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Tarifa de transporte</label>
                <div class="">
                  <input type="number" name="tarifa_transporte" value="{{ $CP->tarifa_transporte }}" step="any" onkeyup="calculoImporteDesdeTarifa({{$cantidadCP}})" class="form-control" required>
                </div>
              </div>
              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Tarifa Cliente</label>
                <div class="">
                  <input type="number" name="tarifa_cliente" value="{{ $CP->tarifa_cliente }}" step="any" onkeyup="calculoImporteDesdeTarifaCliente({{$cantidadCP}})" class="form-control" required>
                </div>
              </div>

              <div class="form-group col-md-4 col-xs-12">
                <label class="control-label">Mercancia</label>
                <div class="">
                  <select name="id_mercancia" class="form-control" required>
                    <option value="">Seleccionar mercancia</option>
                    @foreach ($mercancias as $mercancia)
                      <option value="{{ $mercancia->id }}"
                          @if ($CP->id_mercancia == $mercancia->id)
                            selected
                          @endif
                        >{{ $mercancia->nombre_merca }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group col-md-12 col-xs-12">
                <hr />
              </div>
			        @for ($i = 0; $i < $cantidadCP; $i++)
              <div class="row">
                <div class="form-group col-md-3 col-xs-12">
                  <label class="control-label">N° Carta de porte</label>
                  <div class="">
                    <input type="number" value="{{ $CP->ncporte }}" name="nCartaporte{{$i}}" class="form-control" required>
                  </div>
                </div>

                @if($create)
                <div class="form-group col-md-4 col-xs-12">
                  <label class="control-label">Chofer</label>
                  <div class="">
                    <select name="chofer{{$i}}" class="form-control" required>
                      <option value="">Seleccionar cliente</option>
                      @foreach ($choferes as $chofer)
                        @if($chofer->idUnidad != null)
                          <option value="{{ $chofer->id }}">{{ $chofer->nombre }} {{ $chofer->apellido }} ({{ $chofer->cuit }})</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                @endif
                
                <div class="form-group col-md-2 col-xs-12">
                  <label class="control-label">%</label>
                  <div class="">
                    <input type="number" name="porcentaje{{$i}}" value="{{ $CP->porcentaje }}" onkeyup="calculoImporteDesdePorcentaje({{$i}})" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-md-3 col-xs-12">
                  <label class="control-label">Kilos</label>
                  <div class="">
                    <input type="number" name="kilo{{$i}}" value="{{ $CP->kilo }}" onkeyup="calculoImporteDesdeKilo({{$i}})" class="form-control" step="any" required>
                  </div>
                </div>
                <div class="form-group col-md-2 col-xs-12">
                  <label class="control-label">$ Importe Trans.</label>
                  <div class="">
                    <input type="number" name="importe_transporte{{$i}}" value="{{ $CP->importe_transporte }}" class="form-control" readonly="readonly" required>
                  </div>
                </div>

                <div class="form-group col-md-2 col-xs-12">
                  <label class="control-label">$ Importe %.</label>
                  <div class="">
                    <input type="number" name="importe_porcentaje{{$i}}" value="{{ $CP->importe_porcentaje }}" class="form-control" readonly="readonly" required>
                  </div>
                </div>

                <div class="form-group col-md-2 col-xs-12">
                  <label class="control-label">$ Importe Cliente</label>
                  <div class="">
                    <input type="number" name="importe_cliente{{$i}}" value="{{ $CP->importe_cliente }}" class="form-control" readonly="readonly" required>
                  </div>
                </div>
                <div class="form-group col-md-12 col-xs-12">
                <hr />
                </div>
                
              </div>
			        @endfor
              <div class="form-group col-md-12 col-xs-12">
              <button type="submit" class="btn btn-success">{{$create ? 'Crear' : 'Editar'}} carta de porte</button>
              <a href="{{ route('waybills.index') }}" class="btn btn-default">Volver</a>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section("scripts")
<script>

/* Formula para calcular % */
function calculoImporteDesdePorcentaje(e) {
    var porcentaje = document.getElementsByName("porcentaje"+e)[0].value;
    var importe_transporte = document.getElementsByName("importe_transporte"+e)[0].value;
    var div100 = 100;
    calculo = (importe_transporte * porcentaje) / div100;

    document.getElementsByName("importe_porcentaje"+e)[0].value = calculo;
}



/* Formula para Transporte */
function calculoImporteDesdeKilo(e) {
  var tarifa_transporte = document.getElementsByName("tarifa_transporte")[0].value;
  var tarifa_cliente = document.getElementsByName("tarifa_cliente")[0].value;
  var kilo = document.getElementsByName("kilo"+e)[0].value;
  calculo_transporte = (kilo / 1000) * tarifa_transporte;
  calculo_cliente = (kilo / 1000) * tarifa_cliente;
  document.getElementsByName("importe_transporte"+e)[0].value = calculo_transporte;
  document.getElementsByName("importe_cliente"+e)[0].value = calculo_cliente;

  calculoImporteDesdePorcentaje(e);
}

function calculoImporteDesdeTarifa(e) {
  for (var i = 0; i < e; i++) {
    var tarifa_transporte = document.getElementsByName("tarifa_transporte")[0].value;
    var kilo = document.getElementsByName("kilo"+i)[0].value;
    calculo_transporte = (kilo / 1000) * tarifa_transporte;
    document.getElementsByName("importe_transporte"+i)[0].value = calculo_transporte;

    calculoImporteDesdePorcentaje(i);
  }
}

/* Formula para cliente */
function calculoImporteDesdeTarifaCliente(e) {
  for (var i = 0; i < e; i++) {
    var tarifa_cliente = document.getElementsByName("tarifa_cliente")[0].value;
    var kilo = document.getElementsByName("kilo"+i)[0].value;
    calculo = (kilo / 1000) * tarifa_cliente;
    document.getElementsByName("importe_cliente"+i)[0].value = calculo;
  }
}


</script>
@endsection

