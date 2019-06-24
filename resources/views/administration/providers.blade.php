@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="row">
  <div class="col-md-12 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Proveedores registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($proveedores) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-4">Raz&oacute;n Social</th>
                <th class="col-sm-4">Tel&eacute;fono</th>
                <th class="col-sm-4">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($proveedores as $proveedor)
              <tr>
                <td>{{ $proveedor->razonsocial}}</td>
                <td>{{ $proveedor->tel}}</td>
                <td>
                  <a href="{{ route('providers.show', [ 'provider' => $proveedor->id ]) }}" class="label label-default">
                    <i class="fa fa-eye"></i> Ver
                  </a>
                  &nbsp;-&nbsp;
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $proveedor->id}}">
                    <i class="fa fa-trash-o"></i> Dar de baja
                  </a>
                  <!- ModalEliminar -->
                  <div class="modal fade" id="confirmarEliminar{{ $proveedor->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="{{ route('providers.destroy', ['provider' => $proveedor->id])}}" method="POST" class="form-inline">
                        <div class="modal-body">
                          <h5>Estas seguro que quiere dar de baja este proveedor?</h5>
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
          {{ $proveedores->links() }}
          @else
            <p>No hay proveedores registrados</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar proveedor dado de baja</a>
            <a class="btn btn-primary" href="{{ route('providers.create')}}">Registrar proveedor</a>
          </div>
        </div>
      </div>
      
      <!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Proveedor dados de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($proveedoresEliminados) > 0)
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Raz&oacute;n Social</th>
                    <th>CUIT</th>
                    <th>Acci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($proveedoresEliminados as $proveedorEliminado)
                  <tr>
                    <td>{{ $proveedorEliminado->id}}</td>
                    <td>{{ $proveedorEliminado->razonsocial}}</td>
                    <td>{{ $proveedorEliminado->cuit}}</td>
                    <td>
                      <a href=" {{ route('providers.restore', [ 'provider' => $proveedorEliminado->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay proveedores para recuperar</p>
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