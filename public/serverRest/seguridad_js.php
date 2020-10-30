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
                  autenticado(0);
                 }
                else
                  $_SESSION["ultimoAcceso"] = $ahora;
                  autenticado($_SESSION["nivel"]);
          }
} else {
  autenticado(0);
}

function autenticado ($datos) {
    echo json_encode($datos);
}