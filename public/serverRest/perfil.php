<?php
spl_autoload_register(function ($nombre_clase) {
  require_once  'Class/' . $nombre_clase . '.Class.php';
});
header("Content-Type:application/json");
$datos = MisFunciones::obtenerSerializado(); //new User (MisFunciones::obtenerUsuarioLogeado());
$resultado = [
	'usuario' => $datos->usuario->getUsuario(),
	'nom_ape' => $datos->usuario->getNom_ape(),
	'nivel' => $datos->usuario->getNivelNombre(),
	'vence' => $datos->usuario->getVenceEn(),
	'ultLogin' => "No Disponible",
];
echo json_encode($resultado);