<?php
include_once ('ComfuegPHPClasses.php');
$datos = new Referencia();
$datos->usuario = new Usuario();

if (!isset($_SESSION)) {
  session_name("loginUsuario");
  session_start();
}
//antes de hacer los cálculos, compruebo que el usuario está logueado
//utilizamos el mismo script que antes
if (isset($_SESSION["autentificado"]))
{
  if ($_SESSION["autentificado"] != "SI")
      {
      //si no está logueado lo envío a la página de autentificación
        noAutenticado($datos);
      }
      else
          {
            //sino, calculamos el tiempo transcurrido
            $fechaGuardada = $_SESSION["ultimoAcceso"];
            $ahora = date("Y-n-j H:i:s");
            $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
            //comparamos el tiempo transcurrido
             if($tiempo_transcurrido >= 600)
                 {
                //si pasaron 10 minutos o más
                  session_destroy(); // destruyo la sesión
                  noAutenticado($datos);
                 }
                else
                {
                  $_SESSION["ultimoAcceso"] = $ahora;
                  autenticado($datos);
                  $datos->usuario->obtenerUnUsuarioPorId($_SESSION["idUsuario"]);
                }
          }
} else {
  noAutenticado($datos);
}

function noAutenticado ($datos) {
    $datos->setAutenticado(0);
    $datos->setPerfil(0);
    $datos->setLogin(1);
}
function autenticado ($datos) {
    $datos->setAutenticado(1);
    $datos->setPerfil(1);
    $datos->setLogin(0);  
}