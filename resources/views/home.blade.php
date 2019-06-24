@extends('layouts.app')


@section('content')
<div class="row">
	<div class="col-md-3 col-xs-12">
		<div class="metric">
			<span class="icon"><i class="fa fa-user-o"></i></span>
			<p>
				<span class="number">{{$clientes}}</span>
				<span class="title">Clientes</span>
			</p>
		</div>
	</div>
	<div class="col-md-3 col-xs-12">
		<div class="metric">
			<span class="icon"><i class="fa fa-id-card-o"></i></span>
			<p>
				<span class="number">{{$choferes}}</span>
				<span class="title">Choferes</span>
			</p>
		</div>
	</div>
	<div class="col-md-3 col-xs-12">
		<div class="metric">
			<span class="icon"><i class="fa fa-id-card-o"></i></span>
			<p>
				<span class="number">{{$unidades}}</span>
				<span class="title">Unidades</span>
			</p>
		</div>
	</div>
	<div class="col-md-3 col-xs-12">
		<div class="metric">
			<span class="icon"><i class="fa fa-id-card-o"></i></span>
			<p>
				<span class="number">{{$duenos}}</span>
				<span class="title">Dueños</span>
				
			</p>
		</div>
	</div>

</div>
@endsection