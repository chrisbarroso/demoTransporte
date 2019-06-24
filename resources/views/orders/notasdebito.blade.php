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
        <h3 class="panel-title">Notas de debito registradas</h3>
      </div>
      <div class="panel-body no-padding">
        <div class="col-md-12">
        @if (count($notas) > 0)
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Categoria</th>
                <th>Nombre</th>
                <th>Importe</th>
                <th>Motivo</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($notas as $nota)
              <tr>
                <td>{{ $nota->created_at->format('d/m/Y') }}</td>
                <td>{{ $nota->quien }}</td>
                @if($nota->quien == "Dueño") 
                <td>{{ $nota->dueno->nombre }} {{ $nota->dueno->apellido }}</td>
                @endif 
                @if($nota->quien == "Cliente")
                <td>{{ $nota->cliente->razonsocial }}</td>
                @endif
                @if($nota->quien == "Proveedor")
                <td>{{ $nota->proveedor->razonsocial }}</td>
                @endif
                
                <td>${{ number_format($nota->importe, 2) }}</td>
                <td>{{ $nota->motivo }}</td>
                <td>
                  <a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $nota->id}}">
										<i class="fa fa-trash-o"></i> Dar de baja
									</a>
									<!- ModalEliminar -->
									<div class="modal fade" id="confirmarEliminar{{ $nota->id}}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
											
												<form action="{{ route('debito.destroy', ['debito' => $nota->id]) }}" method="POST" class="form-inline">
													<div class="modal-body">
														<h5>Estas seguro que quiere dar de baja esta nota de debito?</h5>
														{{ csrf_field() }} {{ method_field('DELETE') }}
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-success">
															<i class="fa fa-trash-o"></i> Confirmar</button>
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
          {{ $notas->links() }}
          @else
            <p>No hay notas de debito registrados</p>
          @endif
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-primary" href="{{ route('debito.create')}}">Registrar nota de debito</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@section('scripts')

@endsection
