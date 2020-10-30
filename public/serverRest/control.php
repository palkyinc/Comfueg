<?php
//vemos si el usuario y contraseña es váildo
spl_autoload_register(function ($nombre_clase) {
  require_once 'Class/' . $nombre_clase . '.Class.php';
});

header("Content-Type:application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $data = file_get_contents('php://input');
  $data = json_decode($data, false);
    $nUsuario = new Usuario();
    if ($nUsuario->obtenerUnUsuarioPorNombre($data->usuario))
      {
        $contraseniaEsValida = $nUsuario->comprobarContrasenia($data->contrasena);
        if ($contraseniaEsValida == 1 && !isset($data->contrasenaNew) && !isset($data->contrasenaNew2))
            {
                setDatosSession();
                responder($contraseniaEsValida);
            }elseif ((isset($data->contrasenaNew) && isset($data->contrasenaNew2) && $contraseniaEsValida === 4) || ($contraseniaEsValida == 1 && isset($data->contrasenaNew) && isset($data->contrasenaNew2)))
                  {
                    $respuesta = ($nUsuario->comprobarNuevaContrasenia($data->contrasenaNew, $data->contrasenaNew2));
                    if ( $respuesta === 1 ) {
                      setDatosSession();
                    }
                    responder($respuesta);
                  }else {
                          //si mal contraseña le mando otra vez a la portada
                          responder($contraseniaEsValida);
                        }
      }else{
              //si no existe le mando otra vez a la portada
              responder(2);
            }
}
function responder ($a) {
  echo json_encode($a);
}
function setDatosSession () {
  $datosSesion = MisFunciones::obtenerSerializado();
  $datosSesion->autenticado();
  $datosSesion->SetUltAcceso();
  global $nUsuario;
  $datosSesion->setUsuarioPorId($nUsuario->getId());
  MisFunciones::guardarSesion($datosSesion);
  //MisFunciones::imprimirConPrintR($datosSesion);
  //sFunciones::imprimirConPrintR($nUsuario->getId());
  
  /*global $contraseniaEsValida;
  session_name("loginUsuario");
  //asigno un nombre a la sesión para poder guardar diferentes datos
  session_start();
  // inicio la sesión
  $_SESSION["autentificado"]= "SI";
  //defino la sesión que demuestra que el usuario está autorizado
  $_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s");
  //defino la fecha y hora de inicio de sesión en formato aaaa-mm-dd hh:mm:ss
  $_SESSION["idUsuario"]= $nUsuario->getId();
  //defino el id del usuario
  $_SESSION["nivel"]= $nUsuario->getNivelId();
  //defino el id del usuario*/
}