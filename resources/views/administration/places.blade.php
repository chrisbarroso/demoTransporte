@extends('layouts.app')

@section('content')
<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="row" id="places">
  <div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Lugares registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($lugares) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Lugar</th>
                <th>Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($lugares as $lugar)
              <tr>
                <td>{{ $lugar->lugar}}</td>
                <td>
                  <a href="{{ route('places.show', [ 'place' => $lugar->id ]) }}" class="label label-default">
                    <i class="fa fa-eye"></i> Ver
                  </a>
                  &nbsp;-&nbsp;
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $lugar->id}}">
                    <i class="fa fa-trash-o"></i> Dar de baja
                  </a>
                  <!- ModalEliminar -->
                  <div class="modal fade" id="confirmarEliminar{{ $lugar->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="{{ route('places.destroy', ['place' => $lugar->id])}}" method="POST" class="form-inline">
                        <div class="modal-body">
                          <h5>Estas seguro que quiere dar de baja este lugar?</h5>
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
          {{ $lugares->links() }}
          @else
            <p>No hay lugares registrados</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar lugar dado de baja</a>
            <a class="btn btn-primary" href="{{ route('places.create')}}">Registrar lugar</a>
          </div>
        </div>
      </div>
      
      <!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Lugares dado de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($lugaresEliminados) > 0)
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Lugar</th>
                    <th>Acci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($lugaresEliminados as $lugarEliminado)
                  <tr>
                    <td>{{ $lugarEliminado->id }}</td>
                    <td>{{ $lugarEliminado->lugar }}</td>
                    <td>
                      <a href=" {{ route('places.restore', [ 'place' => $lugarEliminado->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay lugares para recuperar</p>
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

@section('scripts')

@endsection
