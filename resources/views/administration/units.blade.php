@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="row" id="clients">
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
          <form action="{{ route('units.index') }}" method="GET" class="">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="Dominio">Dominio:</label>
              <input type="text" name="searchDominio" class="form-control" value="{{ Request::get('searchDominio') }}">
            </div>
            <button type="submit" class="btn btn-default">Filtrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Unidades registradas</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($unidades) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-4">Dominio</th>
                <th class="col-sm-4">Dueño</th>
                <th class="col-sm-4">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($unidades as $unidad)
              <tr>
                <td class="text-uppercase">{{ $unidad->dominio }}</td>
                <td>{{ $unidad->owner->nombre}} {{ $unidad->owner->apellido }}</td>
                <td>
                  <a href="{{ route('units.show', [ 'unity' => $unidad->id ]) }}" class="label label-default">
                    <i class="fa fa-eye"></i> Ver
                  </a>
                  
                  
                  
                  &nbsp;-&nbsp;
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $unidad->id}}">
                    <i class="fa fa-trash-o"></i> Dar de baja
                  </a>
                  <!- ModalEliminar -->
                  <div class="modal fade" id="confirmarEliminar{{ $unidad->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        @if (!$unidad->usado)
                        <form action="{{ route('units.destroy', ['unity' => $unidad->id])}}" method="POST" class="form-inline">
                        <div class="modal-body">
                          <h5>Estas seguro que quiere dar de baja esta unidad?</h5>
                          {{ csrf_field() }}
                          {{ method_field('DELETE') }}
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"></i> Confirmar</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        </form>
                        @else
                        <div class="modal-body">
                          <h5>Esta unidad no se puede dar de baja porque tiene asociado un chofer</h5>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <!- finModalEliminar -->
                  
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $unidades->links() }}
          @else
            <p>No hay unidades registradas</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar unidad dada de baja</a>
            <a class="btn btn-primary" href="{{ route('units.create')}}">Registrar unidad</a>
          </div>
        </div>
      </div>
      
      <!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Unidades dada de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($unidadesEliminados) > 0)
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Dominio</th>
                    <th>Dueño</th>
                    <th>Acci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($unidadesEliminados as $unidadEliminado)
                  <tr>
                    <td>{{ $unidadEliminado->id}}</td>
                    <td class="text-uppercase">{{ $unidadEliminado->dominio}}</td>
                    <td>{{ $unidadEliminado->owner->nombre }} {{ $unidadEliminado->owner->apellido }}</td>
                    <td>
                      <a href=" {{ route('units.restore', [ 'unity' => $unidadEliminado->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay unidades para recuperar</p>
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
</div>
@endsection

