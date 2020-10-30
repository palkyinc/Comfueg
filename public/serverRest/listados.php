<?php
require_once 'ComfuegPHPClasses.php';
header("Content-Type:application/json");
$security = MisFunciones::seguridad();
$dato = (isset($_GET['dato'])) ? ($_GET['dato']) : 0;
$tabla = (isset($_GET['tabla'])) ? ($_GET['tabla']) : 0;
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		if ($security != 0) {
			if ( $tabla && $dato ){
				$consulta = "SELECT id, $dato FROM $tabla";
				$respuesta = MisFunciones::buscarEnBaseArray($consulta);
				if (!$respuesta) {
					$respuesta = "Sin resultados.";
				}
			}else $respuesta = "Error en GET";

		} else $respuesta = "3528";
		break;
	default:
		$respuesta = "Error en el Método";
		break;
}
echo json_encode($respuesta);
