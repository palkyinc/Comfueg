<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Intranet CF</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="/cssboot/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/cssboot/easy-autocomplete.min.css">
	<link rel="stylesheet" href="/cssboot/easy-autocomplete.themes.min.css">
	<script src="/jsboot/jquery-3.5.1.slim.min.js"></script>
	<script src="/jsboot/popper.min.js"></script>
	<script src="/jsboot/bootstrap.min.js"></script>
	<script src="/jsboot/sweetalert.min.js"></script>
	<script src="/jsboot/jquery.easy-autocomplete.min.js"></script>
	<link rel="stylesheet" href="/css/estilos.css">
	
</head>
<body>

	<header>

		<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav mr-auto">
		      <li class="nav-item {{  $principal ?? ''}}">
		        <a class="nav-link" href="/inicio">Principal</a>
		      </li>
		      <li class="nav-item {{  $contratos ?? ''}}">
		        <a class="nav-link" href="/contratos">Contratos</a>
		      </li>
		      <li class="nav-item {{  $nodos ?? ''}}">
		        <a class="nav-link" href="/nodos">Nodos</a>
		      </li>
		      <li class="nav-item dropdown">
		        <a class="nav-link dropdown-toggle {{ $datos ?? ''}}" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          Datos
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
		          <a class="dropdown-item" href="/adminAntenas">Antenas</a>
		          <a class="dropdown-item" href="/adminBarrios">Barrios</a>
		          <a class="dropdown-item" href="/adminCalles">Calles</a>
		          <a class="dropdown-item" href="/adminCiudades">Ciudades</a>
		          <a class="dropdown-item" href="/adminCodigosDeArea">Códigos de Área</a>
		          <a class="dropdown-item" href="/adminDirecciones">Direcciones</a>
		          <a class="dropdown-item" href="/adminEquipos">Equipos</a>
		          <a class="dropdown-item" href="/adminNiveles">Niveles</a>
		          <a class="dropdown-item" href="/adminPaneles">Paneles</a>
		          <a class="dropdown-item" href="/adminProductos">Productos</a>
		          <a class="dropdown-item" href="/adminPlanes">Planes</a>
		          <a class="dropdown-item" href="/adminSites">Sitios</a>
		          <div class="dropdown-divider"></div>
		          <a class="dropdown-item" href="/adminClientes">Clientes</a>
		        </div>
		      </li>
		    </ul>
		  </div>
		    <div class="collapse navbar-collapse" id="navbarSupportedContent2">
		  	<a class="navbar-brand" href="#">
			    <img src="../img/miniLogoCF.jpg" width="30" height="30" class="d-inline-block align-top" alt="logotipo" loading="lazy">
			    Comunicaciones Fueguinas SRL
			</a>
			</div>
		</nav>
	</header>