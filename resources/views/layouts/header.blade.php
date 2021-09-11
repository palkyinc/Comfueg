<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Intranet CF</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="cssboot/easy-autocomplete.min.css">
	<script src="js/Vendor/sweetalert2@10.js"></script>
	<link rel="stylesheet" href="/css/estilos.css">
	
</head>
<body>

	<script src="js/Vendor/jquery-3.5.1.slim.min.js"></script>
	<script src="jsboot/jquery.easy-autocomplete.min.js"></script>
    <script src="js/Vendor/popper.min.js"></script>
    <script src="js/Vendor/bootstrap.min.js"></script>
	<!-- Charting library -->
    <script src="js/Vendor/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="js/Vendor/chartisan_echarts.js"></script>
	
	<header>

		<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
		  <div class="collapse navbar-collapse">
		    <ul class="navbar-nav mr-auto">
		      <li class="nav-item {{  $principal ?? ''}}">
		        <a class="nav-link" href="/inicio">Principal</a>
		      </li>
		    <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle {{  $contracts ?? ''}}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Contratos
				</a>
				<div class="dropdown-menu" aria-labelledby="nodosNavbarDropdown">
					<a class="dropdown-item disabled" href="/altaContrato">Alta</a>
					<a class="dropdown-item disabled" href="">Consulta</a>
				</div>
			</li>	
		    <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle {{  $internet ?? ''}}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Internet
				</a>
				<div class="dropdown-menu" aria-labelledby="nodosNavbarDropdown">
					<a class="dropdown-item" href="/adminContratos">Abonos</a>
					<a class="dropdown-item disabled" href="">Status de Contrato</a>
					<a class="dropdown-item" href="/adminIssues">Tickets</a>
				</div>
			</li>	
		      <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle {{  $nodos ?? ''}}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Nodos
				</a>
				<div class="dropdown-menu" aria-labelledby="nodosNavbarDropdown">
					<a class="dropdown-item" href="/adminControlPanelNodos">Status Paneles</a>
					<a class="dropdown-item" href="/adminPanelLogs">Historial Test Paneles</a>
					<a class="dropdown-item" href="/adminNodos">Nodos Info</a>
					<a class="dropdown-item" href="/adminIncidencias">Incidencias Globales</a>
				  	<a class="dropdown-item" href="/adminDeudasTecnica">Deuda Técnica</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle {{  $providers ?? ''}}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Proveedores
				</a>
				<div class="dropdown-menu" aria-labelledby="nodosNavbarDropdown">
					<a class="dropdown-item" href="/adminInterfaces">Interfaces</a>
					<a class="dropdown-item" href="/adminProveedores">Proveedores</a>
					<a class="dropdown-item" href="/adminPlanes">Planes</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle {{ $datos ?? ''}}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Datos
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="/adminAntenas">Antenas</a>
					<a class="dropdown-item" href="/adminBarrios">Barrios</a>
					<a class="dropdown-item" href="/adminPanelhasBarrio">Barrios por panel</a>
		        	<a class="dropdown-item" href="/adminCalles">Calles</a>
		        	<a class="dropdown-item" href="/adminCiudades">Ciudades</a>
		        	<a class="dropdown-item" href="/adminCodigosDeArea">Códigos de Área</a>
		        	<a class="dropdown-item" href="/adminDirecciones">Direcciones</a>
		        	<a class="dropdown-item" href="/adminEquipos">Equipos</a>
		        	<a class="dropdown-item" href="/adminPaneles">Dispositivos</a>
		        	<a class="dropdown-item" href="/adminProductos">Artículos</a>
		        	<a class="dropdown-item" href="/adminSites">Sitios</a>
		          <div class="dropdown-divider"></div>
		          <a class="dropdown-item" href="/adminClientes">Clientes</a>
		        </div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle {{ $sistema ?? ''}}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Sistema
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="/adminUsers">Usuarios</a>
					<a class="dropdown-item" href="/adminRoles">Roles</a>
					<a class="dropdown-item" href="/adminPermissions">Permisos</a>
					<a class="dropdown-item" href="/adminMailGroups">Grupos de Mail</a>
					<a class="dropdown-item" href="/adminModelos">Entidades</a>
					<a class="dropdown-item" href="/adminContractTypes">Tipos de Contratos</a>
					<a class="dropdown-item" href="/adminIssuesTitles">Títulos de Tickets</a>
					<a class="dropdown-item disabled" href="/adminLogs">Logs de Sistema</a>
		        </div>
		      </li>
		    </ul>
		  </div>
		    <div class="collapse navbar-collapse">
		  	<a class="navbar-brand" href="#">
			    <img src="/img/miniLogoCF.jpg" width="30" height="30" class="d-inline-block align-top" alt="logotipo" loading="lazy">
			    Comunicaciones Fueguinas SRL
			</a>
			</div>
			@guest
				@if (Route::has('login'))
						<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
				@endif
				
				@if (Route::has('register'))
						<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
				@endif
			@else
				<div class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
						{{ Auth::user()->name }}
					</a>

					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="{{ route('logout') }}"
							onclick="event.preventDefault();
											document.getElementById('logout-form').submit();">
							{{ __('Logout') }}
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
							@csrf
						</form>
					</div>
				</div>
			@endguest
		</nav>
	</header>