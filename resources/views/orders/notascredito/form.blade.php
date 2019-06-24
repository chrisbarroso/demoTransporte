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
        <h3 class="panel-title">Crear</h3>
        <p class="panel-subtitle">Generar nueva nota de credito</p>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <form action="{{ route('credito.store') }}" method="POST">
              {{ csrf_field() }}
              {{ method_field('POST') }}
              <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Seleccione cliente, proveedor o dueño</label>
                <div class="">
                  <select name="quien" id="tipo" onchange="selectForma(this)" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="1">Dueño</option>
                    <option value="2">Cliente</option>
                    <option value="3">Proveedor</option>
                  </select>
                </div>
              </div>

              <div class="form-group col-md-12 col-xs-12" id="formularioDueno" style="display: none;">
                <label class="control-label">Seleccionar dueño</label>
                <div class="">
                  <select name="quien_id" id="dueno" class="form-control" required disabled>
                    <option value="">Seleccionar</option>
                    @foreach($duenos as $dueno)
                    <option value="{{ $dueno->id }}">{{ $dueno->nombre }} {{ $dueno->apellido }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              
              <div class="form-group col-md-12 col-xs-12" id="formularioCliente" style="display: none;">
                <label class="control-label">Seleccionar cliente</label>
                <div class="">
                  <select name="quien_id" id="cliente" class="form-control" required disabled>
                    <option value="">Seleccionar</option>
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->razonsocial }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group col-md-12 col-xs-12" id="formularioProveedor" style="display: none;">
                <label class="control-label">Seleccionar proveedor</label>
                <div class="">
                  <select name="quien_id" id="proveedor" class="form-control" required disabled>
                    <option value="">Seleccionar</option>
                    @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->razonsocial }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Importe</label>
                <div class="">
                  <input type="number" name="importe" class="form-control" require step="any" />
                </div>
              </div>
              

              <div class="form-group col-md-12 col-xs-12">
                <label class="control-label">Motivo</label>
                <div class="">
                  <textarea name="motivo" class="form-control" required></textarea>
                </div>
              </div>

              <div class="form-group col-md-12 col-xs-12">
                <button type="submit" class="btn btn-success">Crear nota de credito</button>
                <a href="{{ route('credito.index') }}" class="btn btn-default">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> 
@endsection

@section("scripts")
<script>
function form(){
    $("#formulario").show();
}
$("#tipo").on("change", form);


function selectForma(selectObject) {
    
  var value = selectObject.value;
  if(value == "1") {
    $("#formularioDueno").show();
    document.getElementById("dueno").disabled = false;
    $("#formularioCliente").hide();
    document.getElementById("cliente").disabled = true;
    $("#formularioProveedor").hide();
    document.getElementById("proveedor").disabled = true;
  }else if(value == "2") {
    $("#formularioCliente").show();
    document.getElementById("cliente").disabled = false;
    $("#formularioDueno").hide();
    document.getElementById("dueno").disabled = true;
    $("#formularioProveedor").hide();
    document.getElementById("proveedor").disabled = true;
  }else if(value == "3") {
    $("#formularioProveedor").show();
    document.getElementById("proveedor").disabled = false;
    $("#formularioDueno").hide();
    document.getElementById("dueno").disabled = true;
    $("#formularioCliente").hide();
    document.getElementById("cliente").disabled = true;
  }

}

</script>
@endsection
