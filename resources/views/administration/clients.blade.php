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
          <form action="{{ route('clients.index') }}" method="GET" class="">
              {{ csrf_field() }}
            <div class="form-group">
              <label for="Raz&oacute;n Social">Raz&oacute;n social:</label>
              <input type="text" name="searchRazonSocial" class="form-control" value="{{ Request::get('searchRazonSocial') }}">
            </div>
            <!--<div class="form-group">
              <label for="cuit">Cuit:</label>
              <input type="text" name="searchCuit" class="form-control" value="{{ Request::get('searchCuit') }}">
            </div>-->
            <button type="submit" class="btn btn-default">Filtrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-xs-12">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">Clientes registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($clientes) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-4">Raz&oacute;n Social</th>
                <th class="col-sm-4">Tel&eacute;fono</th>
                <th class="col-sm-4">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($clientes as $cliente)
              <tr>
                <td>{{ $cliente->razonsocial}}</td>
                <td>{{ $cliente->tel}}</td>
                <td>
                  <a href="{{ route('clients.show', [ 'client' => $cliente->id ]) }}" class="label label-default">
                    <i class="fa fa-eye"></i> Ver
                  </a>
                  &nbsp;-&nbsp;
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $cliente->id}}">
                    <i class="fa fa-trash-o"></i> Dar de baja
                  </a>
                  <!- ModalEliminar -->
                  <div class="modal fade" id="confirmarEliminar{{ $cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="{{ route('clients.destroy', ['client' => $cliente->id])}}" method="POST" class="form-inline">
                        <div class="modal-body">
                          <h5>Estas seguro que quiere dar de baja este cliente?</h5>
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
          {{ $clientes->links() }}
          @else
            <p>No hay clientes registrados</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar cliente dado de baja</a>
            <a class="btn btn-primary" href="{{ route('clients.create')}}">Registrar cliente</a>
          </div>
        </div>
      </div>
      
      <!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Clientes dado de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($clientesEliminados) > 0)
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
                @foreach ($clientesEliminados as $clienteEliminado)
                  <tr>
                    <td>{{ $clienteEliminado->id}}</td>
                    <td>{{ $clienteEliminado->razonsocial}}</td>
                    <td>{{ $clienteEliminado->cuit}}</td>
                    <td>
                      <a href=" {{ route('clients.restore', [ 'client' => $clienteEliminado->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay clientes para recuperar</p>
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
    
    

<!--@section('scripts')
$('#memberModal').modal('show');
@endsection-->
