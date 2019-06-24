@extends('layouts.app')

@section('content')

<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="row">
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
          <form action="{{ route('wallet.index') }}" method="GET" class="">
              {{ csrf_field() }}
            <div class="form-group">
              <label for="Numero de cheque">Numero de cheque:</label>
              <input type="text" name="searchNumeroCheque" class="form-control" value="{{ Request::get('searchNumeroCheque') }}">
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
        <h3 class="panel-title">Cheques registrados</h3>
        <div class="right">
          <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
        </div>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($cheques) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-sm-2">Numero de cheque</th>
                <th class="col-sm-2">Importe</th>
                <th class="col-sm-2">Banco</th>
                <th class="col-sm-4">Observacion</th>  
                <th class="col-sm-2">Acci&oacute;n</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cheques as $cheque)
              <tr>
                <td>{{ $cheque->nro_cheque }}</td>
                <td>${{ number_format($cheque->importeF, 2) }} </td>
                <td>{{ $cheque->banco->nombre }}</td>
                <td title="{{ $cheque->observacion }}">{{ str_limit($cheque->observacion, $limit = 25, $end = '...') }}</td>
                <td>
                  @if($cheque->dado != 1)
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $cheque->id}}">
                    <i class="fa fa-trash-o"></i> Dar de baja
                  </a>
                  <!- ModalEliminar -->
                  <div class="modal fade" id="confirmarEliminar{{ $cheque->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form action="{{ route('wallet.destroy', ['wallet' => $cheque->id])}}" method="POST" class="form-inline">
                          <div class="modal-body">
                            <h5>Estas seguro que quiere dar de baja este cheque?</h5>
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
          {{ $cheques->links() }}
          @else
            <p>No hay cheques registrados</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-primary" href="{{ route('wallet.create')}}">Registrar cheque</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
    
    

<!--@section('scripts')
$('#memberModal').modal('show');
@endsection-->
