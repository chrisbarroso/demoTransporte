@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->

<div class="row" id="advancements">
  <div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Adelantos registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($adelantos) > 0)
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Pedido por (chofer)</th>
              <th>Se liquida (Unidad)</th>
              <th>Unidad pertenece (Dueño)</th>
              <th>Fecha</th>
              <th>Importe</th>
              <th>Liquidado</th>
              <th>Acci&oacute;n</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($adelantos as $adelanto)
            <tr @if($adelanto->liquidado) style="background-color: #41ff65;" @endif>
              <td>{{ $adelanto->driverAde->nombre }} {{ $adelanto->driverAde->apellido }}</td>
              <td>{{ $adelanto->unityAde->dominio }}</td>
              <td>{{ $adelanto->ownerAde->nombre }} {{ $adelanto->ownerAde->apellido }}</td>
              <td>{{ $adelanto->fecha->format('d/m/Y') }}</td>
              <td>${{ number_format($adelanto->importe_total, 2) }}</td>
              <td>
                @if($adelanto->liquidado)
                  Si
                @else
                  No
                @endif
              </td>
              <td>
                <a href="{{ route('advancements.show', [ 'advancement' => $adelanto->id ]) }}" class="label label-default">
                  <i class="fa fa-eye"></i> Ver
                </a>
                &nbsp;-&nbsp;
                <a class="label label-default" data-toggle="modal" data-target="#imprimir{{ $adelanto->id}}">
                  <i class="fa fa-print"></i> Imprimir
                </a>
                
                <!- ModalImpresion -->
                <div class="modal fade" id="imprimir{{ $adelanto->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body" id="imp{{ $adelanto->id}}" style="font-size: 16px; font-family: Arial Narrow,Arial,sans-serif;">
                        <p style="float: right;"><img src="{{ asset('assets/img/logo-dark.png') }}" width="250px"></p>
                        <p>Alvear 731 - 2760 - San Antonio de Areco<br />
                        Tel: 02326 454800 - Cel: 02325 659825<br />
                        E-Mail: transguardapampa@yahoo.com.ar
                        </p>
                        <p>San Antonio de Areco, {{ $adelanto->fecha->format('d/m/Y') }}</p>
                        <p>Adelanto numero: {{ $adelanto->id }}</p>
                        <hr style="margin-top: 5px;margin-bottom: 5px;" />
                        <p>Sirvase entregar al Sr/a: {{ $adelanto->driverAde->nombre }} {{ $adelanto->driverAde->apellido }} ({{ $adelanto->driverAde->cuit }})</p>
                        <p>Camion: {{ $adelanto->unityAde->dominio }} - Acoplado: {{ $adelanto->unityAde->dominioAcoplado }}</p>
                        <hr style="margin-top: 5px;margin-bottom: 5px;" />
                        <p>Lo siguiente:</p>
                        <p>
                          Adelanto por: ${{ number_format($adelanto->importe_total, 2) }}
                        </p>
                        <p style="float: right;">
                        ------------------------------------------------------- <br />
                        Firma y aclaracion
                        </p>
                        <br />
                        <p>Original</p>
                        <br />
                        <p style="float: right;"><img src="{{ asset('assets/img/logo-dark.png') }}" width="250px"></p>
                        <p>Alvear 731 - 2760 - San Antonio de Areco<br />
                        Tel: 02326 454800 - Cel: 02325 659825<br />
                        E-Mail: transguardapampa@yahoo.com.ar
                        </p>
                        <p>San Antonio de Areco, {{ $adelanto->fecha->format('d/m/Y') }}</p>
                        <p>Adelanto numero: {{ $adelanto->id }}</p>
                        <hr style="margin-top: 5px;margin-bottom: 5px;" />
                        <p>Sirvase entregar al Sr/a: {{ $adelanto->driverAde->nombre }} {{ $adelanto->driverAde->apellido }} ({{ $adelanto->driverAde->cuit }})</p>
                        <p>Camion: {{ $adelanto->unityAde->dominio }} - Acoplado: {{ $adelanto->unityAde->dominioAcoplado }}</p>
                        <hr style="margin-top: 5px;margin-bottom: 5px;" />
                        <p>Lo siguiente:</p>
                        <p>
                          Adelanto por: ${{ number_format($adelanto->importe_total, 2) }}
                        </p>
                        
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="prints({{$adelanto->id}});">
                          <i class="fa fa-print"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!- finModalImpresion -->
                @if(!$adelanto->liquidado)
                &nbsp;-&nbsp;
                <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $adelanto->id}}">
                  <i class="fa fa-trash-o"></i> Dar de baja
                </a>
                <!- ModalEliminar -->
                <div class="modal fade" id="confirmarEliminar{{ $adelanto->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <form action="{{ route('advancements.destroy', ['advancement' => $adelanto->id])}}" method="POST" class="form-inline">
                      <div class="modal-body">
                        <h5>Estas seguro que quiere dar de baja este adelanto?</h5>
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"></i> Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!- finModalEliminar -->
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
          </table>
          {{ $adelantos->links() }}
        @else
          <p>No hay adelantos registrados</p>
        @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-primary" href="{{ route('advancements.create')}}">Registrar adelanto</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section("scripts")
<script >
function prints(id) {
  $('#imprimir'+id).modal('hide');
  var d = document.getElementById('imprimir'+id);
  d.style.display = 'none';
  $('.modal-backdrop').remove();

  var contenido = document.getElementById('imp'+id).innerHTML;
  var contenidoOriginal = document.body.innerHTML;
  document.body.innerHTML = contenido;
  
  window.print();
  document.body.innerHTML = contenidoOriginal;
};
</script>
@endsection