@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="row" id="drivers">
  <div class="col-md-8 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Choferes registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($choferes) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-3">Nombre y apellido</th>
                <th class="col-sm-3">Cuit</th>
                <th class="col-sm-3">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($choferes as $chofer)
              <tr>
                <td>{{ $chofer->nombre}} {{ $chofer->apellido}}</td>
                <td>{{ $chofer->cuit}}</td>
                <td>
                  <a href="{{ route('drivers.show', [ 'driver' => $chofer->id ]) }}" class="label label-default">
                    <i class="fa fa-eye"></i> Ver
                  </a>
                  &nbsp;-&nbsp;
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $chofer->id}}">
                    <i class="fa fa-trash-o"></i> Dar de baja
                  </a>
                  <!- ModalEliminar -->
                  <div class="modal fade" id="confirmarEliminar{{ $chofer->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="{{ route('drivers.destroy', ['driver' => $chofer->id]) }}" method="POST" class="form-inline">
                        <div class="modal-body">
                          <h5>Estas seguro que quiere dar de baja este chofer?</h5>
                          @if (isset($chofer->unity->dominio)) 
                            <p>Se va a liberar la unidad: patente (<b class="text-uppercase">{{ $chofer->unity->dominio }}</b>)</p>
                          @endif
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
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $choferes->links() }}
          @else
            <p>No hay choferes registrados</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-left">
            <a class="btn btn-primary" href="{{ route('drivers.create')}}">Registrar chofer</a>
            <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar chofer dado de baja</a>
          </div>
        </div>
      </div>
      
      <!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Choferes dado de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($choferesEliminados) > 0)
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nombre y apellido</th>
                    <th>CUIT</th>
                    <th>Acci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($choferesEliminados as $choferEliminado)
                  <tr>
                    <td>{{ $choferEliminado->id }}</td>
                    <td>{{ $choferEliminado->nombre }} {{ $choferEliminado->apellido }}</td>
                    <td>{{ $choferEliminado->cuit }}</td>
                    <td>
                      <a href=" {{ route('drivers.restore', [ 'chofer' => $choferEliminado->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay choferes para recuperar</p>
              @endif
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <!- finModalEliminados -->
    </div>
  </div>
  <div class="col-md-4 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Filtros</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form action="{{ route('drivers.index') }}" method="GET" class="">
              {{ csrf_field() }}
            <div class="form-group">
              <label for="Nombre">Nombre:</label>
              <input type="text" name="searchNombre" class="form-control" value="{{ Request::get('searchNombre') }}">
            </div>

            <div class="form-group">
              <label for="Apellido">Apellido:</label>
              <input type="text" name="searchApellido" class="form-control" value="{{ Request::get('searchApellido') }}">
            </div>
            
            <button type="submit" class="btn btn-default">Filtrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection