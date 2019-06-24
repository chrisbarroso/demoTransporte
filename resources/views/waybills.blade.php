@extends('layouts.app') @section('content')
<!-- Componente alertas -->
@if (Cookie::get('AlertType'))
  @include('components.alert', array('AlertType' => Cookie::get('AlertType'), 'Msj' => Cookie::get('Msj')))
@endif
<!-- ------------------ -->
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Generar carta/s de porte</h3>
				<div class="right">
					<button type="button" class="btn-toggle-collapse">
						<i class="lnr lnr-chevron-up"></i>
					</button>
				</div>
			</div>
			<div class="panel-body no-padding">
				<div class="col-md-12">
					<form action="{{ route('waybills.create') }}" method="GET">
						{{ csrf_field() }} {{ method_field('GET') }}
						<div class="form-group col-md-12 col-xs-12">
							<span class="text-danger">
								<b>La cantidad de carta/s de porte elegida es para la misma partida, destino, tarifa y fecha. En caso contrario, cargue
									de a una.</b>
							</span>
						</div>
						<div class="form-group col-md-6 col-xs-12">
							<label class="control-label">Clientes</label>
							<div class="">
								<select name="cliente" class="form-control" required>
									<option value="">Seleccionar cliente</option>
									@foreach ($clientes as $cliente)
									<option value="{{ $cliente->id }}">{{ $cliente->razonsocial }} ({{ $cliente->cuit }})</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group col-md-6 col-xs-12">
							<label class="control-label">Cantidad de carta/s de porte</label>
							<div class="">
								<input type="number" name="cantidadCP" class="form-control" required>
							</div>
						</div>

						<div class="form-group col-md-12 col-xs-12">
							<button type="submit" class="btn btn-success">Generar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Filtros</h3>
				<div class="right">
					<button type="button" class="btn-toggle-collapse">
						<i class="lnr lnr-chevron-up"></i>
					</button>
				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<form action="{{ route('waybills.index') }}" method="GET" class="">
						{{ csrf_field() }}
						<div class="form-group">
							<label for="Raz&oacute;n Social">N° Carta de Porte:</label>
							<input type="text" name="searchNCPorte" class="form-control" value="{{ Request::get('searchNCPorte') }}">
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
				<h3 class="panel-title">Cartas de porte generadas</h3>
				<div class="right">
					<button type="button" class="btn-toggle-collapse">
						<i class="lnr lnr-chevron-up"></i>
					</button>
				</div>
			</div>
			<div class="panel-body no-padding">
				<div class="col-md-12">
					@if (count($cps) > 0)
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-sm-4">N° carta de porte</th>
								<th class="col-sm-4">Cliente</th>
								<th class="col-sm-4">Acci&oacute;n</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($cps as $cp)
							<tr>
								<td class="text-uppercase">{{ $cp->ncporte }}</td>
								<td> {{ $cp->client->razonsocial }} </td>
								<td>
									<a href="{{ route('waybills.show', [ 'waybill' => $cp->id ]) }}" class="label label-default">
										<i class="fa fa-eye"></i> Ver
									</a>
									&nbsp;-&nbsp;
									@if ((!$cp->liquidado_dueno) && (!$cp->liquidado_cliente))
									<a class="label label-danger" data-toggle="modal" data-target="#confirmarEliminar{{ $cp->id}}">
										<i class="fa fa-trash-o"></i> Dar de baja
									</a>
									<!- ModalEliminar -->
									<div class="modal fade" id="confirmarEliminar{{ $cp->id}}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
											
												<form action="{{ route('waybills.destroy', ['waybill' => $cp->id])}}" method="POST" class="form-inline">
													<div class="modal-body">
														<h5>Estas seguro que quiere dar de baja esta carta de porte?</h5>
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
									@else
									<a class="label label-default">
										<i class="fa fa-trash-o"></i> Liquidado
									</a>
									@endif

								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{ $cps->links() }} @else
					<p>No hay cartas de porte registradas</p>
					@endif
				</div>
			</div>
			<div class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <a class="btn btn-warning" data-toggle="modal" data-target="#recuperarEliminados">Recuperar carta de porte dada de baja</a>
          </div>
        </div>
			</div>
			<!- ModalEliminados -->
      <div class="modal fade" id="recuperarEliminados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cartas de porte dadas de baja</h5>
            </div>
            <div class="modal-body">
              @if (count($cpsEliminados) > 0)
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>N° Carta de Porte</th>
                    <th>Acci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($cpsEliminados as $cpEliminado)
                  <tr>
                    <td>{{ $cpEliminado->id }}</td>
                    <td>{{ $cpEliminado->ncporte }}</td>
                    <td>
                      <a href=" {{ route('waybills.restore', [ 'waybill' => $cpEliminado->id ]) }}" class="btn btn-warning">
                        <i class="fa fa-history"></i> Recuperar
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No hay cartas de porte para recuperar</p>
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