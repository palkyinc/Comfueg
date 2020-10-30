<?php
spl_autoload_register(function ($nombre_clase) {
  require_once 'serverRest/Class/' . $nombre_clase . '.Class.php';
});
$datos = MisFunciones::validarSesion();
/* 1 = index
** 2 = contratos 
** 3 = internet 
** 4 = antenas 
** 5 = barrios 
** 6 = calles
** 7 = ciudades 
** 8 = coodigos de area 
** 9 = equipos
** 10 = Direcciones 
** 11 = niveles
** 12 = paneles 
** 13 = productos 
** 14 = planes 
** 15 = sitios
** 16 = usuarios 
** 17 = clientes 
** 2 = libre 
*/
if (isset($_GET['enlace']))	{
	//deserializar
	
	//setear nuevo link
	$datos->setMenu($_GET['enlace']);
	//guardar serializado
	MisFunciones::guardarSesion($datos);
	//redirigir
	header('location: index.php');
}