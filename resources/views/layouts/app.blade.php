<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

  <title>{{ config('app.name', 'Trans-GuardaPampa SRL') }}</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- VENDOR CSS -->
  <link href="{{ asset('assets/vendor/linearicons/style.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" rel="stylesheet">
	<!-- MAIN CSS -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
	<!-- FOR DEMO -->
  <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet">
	<!-- GOOGLE FONTS -->
	<link href="{{ asset('assets/css/google/css.css') }}" rel="stylesheet">

  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/favicon.png') }}">

</head>
<body>
  <div id="app">
  <div id="wrapper">
		<!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="brand">
				<a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo-dark.png') }}" alt="{{ config('app.name', 'Trans GuardaPampa') }}" class="img-responsive logo"></a>
			</div>
			<div class="container-fluid">
				<div class="navbar-btn">
					<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
				</div>

				<div class="navbar-btn navbar-btn-right">
					<a class="btn btn-success update-pro" href="#" title="Versión 1.0.0"><i class="fa fa-rocket"></i> <span> Versión 1.0.0</span></a>
				</div>
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
            <!--@if (Auth::guest())

            @else

            @endif -->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle li-nav" data-toggle="dropdown">
							<!--<img src="assets/img/user.png" class="img-circle" alt="Avatar">--> 
							<span>{{ Auth::user() ? Auth::user()->name : 'Desconocido' }}</span> 
							<i class="icon-submenu lnr lnr-chevron-down"></i></a>
              <ul class="dropdown-menu">
								<li>
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="lnr lnr-exit"></i> <span>Logout</span>
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- END NAVBAR -->
		<!-- LEFT SIDEBAR -->
		<div id="sidebar-nav" class="sidebar">
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="{{ url('/') }}" class="{{ Request::path() ==  '/' ? 'active' : ''  }}"><i class="lnr lnr-home"></i> <span>Inicio</span></a></li>
            <!--<li><a href="{{ url('/clients') }}" class="{{ Request::path() ==  'clients' ? 'active' : ''  }}"><i class="lnr lnr-apartment"></i> <span>Clientes</span></a></li>
						<li><a href="{{ url('/drivers') }}" class="{{ Request::path() ==  'drivers' ? 'active' : ''  }}"><i class="lnr lnr-user"></i> <span>Choferes</span></a></li>-->
						<li>
							<a href="#subPages" data-toggle="collapse" class="collapsed"><i class="lnr lnr-cloud-upload"></i> <span>Administracion</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages" class="collapse ">
								<ul class="nav">
									<li><a href="{{ url('/clients') }}" class="">Clientes</a></li>
									<li><a href="{{ url('/providers') }}" class="">Proveedores</a></li>
									<li><hr /></li>
									<li><a href="{{ url('/drivers') }}" class="">Choferes</a></li>
									<li><a href="{{ url('/units') }}" class="">Unidades</a></li>
									<li><a href="{{ url('/owners') }}" class="">Dueños</a></li>
									<li><hr /></li>
									<li><a href="{{ url('/places') }}" class="">Lugares</a></li>
								</ul>
							</div>
						</li>
						<li><a href="{{ url('/wallet') }}" class="{{ Request::path() ==  'wallet' ? 'active' : ''  }}"><i class="lnr lnr-file-empty"></i> <span>Cartera</span></a></li>
						<li><a href="{{ url('/waybills') }}" class="{{ Request::path() ==  'waybills' ? 'active' : ''  }}"><i class="lnr lnr-file-empty"></i> <span>Carta de porte</span></a></li>
						<li>
							<a href="#subOrders" data-toggle="collapse" class="collapsed"><i class="lnr lnr-pencil"></i> <span>Pedidos y notas</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subOrders" class="collapse ">
								<ul class="nav">
									<!--<li>
										<a href="{{ url('/orders/advancements') }}">Adelantos</a>
									</li>-->
									<li>
										<a href="{{ url('/orders/purchases') }}">Ordenes de compras</a>
									</li>
									<li>
										<a href="{{ url('/orders/credito') }}">Notas de credito</a>
									</li>
									<li>
										<a href="{{ url('/orders/debito') }}">Notas de debito</a>
									</li>
								</ul>
							</div>
						</li>

						<li>
							<a href="#subLiquidaciones" data-toggle="collapse" class="collapsed"><i class="lnr lnr-lock"></i> <span>Liquidaciones</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subLiquidaciones" class="collapse ">
								<ul class="nav">
									<li>
										<a href="{{ url('/liquidaciones/dueno') }}">Dueños</a>
									</li>
									<li>
										<a href="{{ url('/liquidaciones/clientes') }}">Clientes</a>
									</li>
								</ul>
							</div>
						</li>

						<li>
							<a href="#subCP" data-toggle="collapse" class="collapsed"><i class="lnr lnr-checkmark-circle"></i> <span>Cobranzas / Pagos</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subCP" class="collapse">
								<ul class="nav">
									<li>
										<a href="{{ url('/cobranzas-pagos/duenos') }}">Pagar Dueños</a>
									</li>
									<li>
										<a href="{{ url('/cobranzas-pagos/clientes') }}">Cobrar Clientes</a>
									</li>
								</ul>
							</div>
						</li>

						<li>
							<a href="#subCC" data-toggle="collapse" class="collapsed"><i class="lnr lnr-briefcase"></i> <span>Cuentas corrientes</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subCC" class="collapse">
								<ul class="nav">
									<li>
										<a href="{{ url('/cuentas-corriente/duenos') }}">Dueños</a>
									</li>
									<li>
										<a href="{{ url('/cuentas-corriente/clientes') }}">Clientes</a>
									</li>
								</ul>
							</div>
						</li>
						
					</ul>
				</nav>
			</div>
		</div>
		<!-- END LEFT SIDEBAR -->
		<!-- MAIN -->
		<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
          @yield('content')
				</div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
	</div>
  </div>
	<!-- END WRAPPER -->
	<!-- Javascript -->
  <script src="{{ mix('js/app.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/mask/jquery.maskedinput.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
  <script src="{{ asset('assets/scripts/klorofil-common.js') }}"></script>
	@section('scripts')
  
	@show
</body>
</html>
