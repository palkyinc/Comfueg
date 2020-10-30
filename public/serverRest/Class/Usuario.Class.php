<?php
class Usuario extends User
{
    private $contrasenia;
    private $pass_ant_1;
    private $pass_ant_2;
    private $pass_ant_3;
    private $pass_ant_4;
    private $pass_ant_5;
    private $pass_ant_6;
    private $ult_acceso;
    private $intentos;
    private $pass_provisorio;
    private $activo_pass_provisorio;

    // constructor new Usuario($a); //parent::
    public function __construct($a = 0)
    {
        parent::__construct();
        $this->contrasenia = null;
        $this->pass_ant_1 = null;
        $this->pass_ant_2 = null;
        $this->pass_ant_3 = null;
        $this->pass_ant_4 = null;
        $this->pass_ant_5 = null;
        $this->pass_ant_6 = null;
        $this->ult_acceso = null;
        $this->intentos = null;
        $this->pass_provisorio = null;
        $this->activo_pass_provisorio = null;
        if ($a != 0) {
          $this->obtenerUnUsuarioPorId ($a);
        }
    }
    public function comprobarNuevaContrasenia ($pass1, $pass2) {
      // return = 6 -> pass1 != pass 2 | 7-> pass repetido | 8 -> Pass no cumple | 9 -> Error al brabar en DB
        if ($pass1 !== $pass2) {
          return 6;
        } elseif (!$this->passEsValido($pass1)) {
                return 8;
                }elseif ($this->passEsRepetido($pass1)) {
                      return 7;
                      }else if ($this->rotarContrasenias($pass1)) {
                              return 1;
                            } else return 9;
    }

    private function passEsValido ($pass) {

      if (strlen($pass) < 8 || strlen($pass) > 15) {
        return false;
      }elseif (strtolower($pass) == $pass) {
            return false;
            } elseif (strtoupper($pass) == $pass) {
                return false;
                } else {
                          for ($i=0; $i < strlen($pass); $i++){
                            if (is_numeric($pass[$i])) {
                              return true;
                            }
                          }
                          return false;
                        }
    }

    private function passEsRepetido ($pass) {
      if ($pass == $this->contrasenia ) {return true;}
      if ($pass == $this->pass_provisorio ) {return true;}
      if ($pass == $this->pass_ant_1 ) {return true;}
      if ($pass == $this->pass_ant_2 ) {return true;}
      if ($pass == $this->pass_ant_3 ) {return true;}
      if ($pass == $this->pass_ant_4 ) {return true;}
      if ($pass == $this->pass_ant_5 ) {return true;}
      if ($pass == $this->pass_ant_6 ) {return true;}
      return false;
    }

    public function getUltLogin () {
      return $this->ult_acceso;
    }

    public function resetPassword () {
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $npass = substr(str_shuffle($permitted_chars), 12, 15);
      $this->pass_provisorio = $npass;
      $this->activo_pass_provisorio = 1;
      $consulta = "UPDATE usuarios SET ACTIVO_PASS_PROVISORIO = $this->activo_pass_provisorio, PASS_PROVISORIO= '$this->pass_provisorio' WHERE ID= " . parent::getId();
      if (MisFunciones::grabarEnBase($consulta)) {
        $to = $this->email; // change to address
        $subject = 'SLAM Reset Password'; // subject of mail
        $body = "Su nuevo Password es: $npass\nUtilicelo para ingresar al portal."; //content of mail
        if (!MisFunciones::mandarEmail($to, $subject, $body)) {
          return true;
        }
      }
      return false;
    }

    private function rotarContrasenias ($pass)
    {
      $this->pass_ant_6 = $this->pass_ant_5;
      $this->pass_ant_5 = $this->pass_ant_4;
      $this->pass_ant_4 = $this->pass_ant_3;
      $this->pass_ant_3 = $this->pass_ant_2;
      $this->pass_ant_2 = $this->pass_ant_1;
      $this->pass_ant_1 = $this->contrasenia;
      $this->contrasenia = $pass;
      $this->activo_pass_provisorio = 0;
      $fecha = date('Y-n-j H:i:s');
      $nuevafecha = strtotime ( '+30 day' , strtotime ( $fecha ) ) ;
      $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
      $this->vence_pass = $nuevafecha;
      $this->intentos = 5;
      $consulta = "UPDATE usuarios SET PASS_ANT_6 = '$this->pass_ant_6', PASS_ANT_5 = '$this->pass_ant_5', PASS_ANT_4 = '$this->pass_ant_4', PASS_ANT_3 = '$this->pass_ant_3', PASS_ANT_2 = '$this->pass_ant_2', PASS_ANT_1 = '$this->pass_ant_1', PASSWORD = '$this->contrasenia', VENCE_PASS = '$this->vence_pass', ACTIVO_PASS_PROVISORIO = $this->activo_pass_provisorio WHERE ID = " . parent::getId();
      if (MisFunciones::grabarEnBase($consulta)) {
        return true;
      }else {return false;}
    }

    private function cargarDatosEnObjeto ($busuario) {
      parent::setId($busuario['ID']);
      parent::setUsuario($busuario['USUARIO']);
      parent::setNom_ape($busuario['NOM_APE']);
      parent::setNivel(new Nivel($busuario['AUT_NIVEL']));
      parent::setVence_pass($busuario['VENCE_PASS']);
      parent::setEmail($busuario['EMAIL']);
      parent::setActivo($busuario['ACTIVO']);
      parent::setCumpleanio($busuario['CUMPLEANIO']);
      $this->contrasenia = $busuario['PASSWORD'];
      $this->pass_ant_1 = $busuario['PASS_ANT_1'];
      $this->pass_ant_2 = $busuario['PASS_ANT_2'];
      $this->pass_ant_3 = $busuario['PASS_ANT_3'];
      $this->pass_ant_4 = $busuario['PASS_ANT_4'];
      $this->pass_ant_5 = $busuario['PASS_ANT_5'];
      $this->pass_ant_6 = $busuario['PASS_ANT_6'];
      $this->ult_acceso = $busuario['ULT_ACCESO'];
      $this->intentos = $busuario['INTENTOS'];
      $this->pass_provisorio = $busuario['PASS_PROVISORIO'];
      $this->activo_pass_provisorio = $busuario['ACTIVO_PASS_PROVISORIO'];
    }

    /*private function obtenerUsuarioMysqliFetch ($consulta, $c) {
      $resultado = mysqli_query($c, $consulta);
      if ($resultado)
          {
            $busuario = mysqli_fetch_array($resultado);
            if ($busuario)
            {
              parent::setId($busuario['ID']);
              parent::setUsuario($busuario['USUARIO']);
              parent::setNom_ape($busuario['NOM_APE']);
              parent::setNivel(new Nivel($busuario['AUT_NIVEL']));
              parent::setVence_pass($busuario['VENCE_PASS']);
              parent::setEmail($busuario['EMAIL']);
              parent::setActivo($busuario['ACTIVO']);
              parent::setCumpleanio($busuario['CUMPLEANIO']);
              $this->contrasenia = $busuario['PASSWORD'];
              $this->pass_ant_1 = $busuario['PASS_ANT_1'];
              $this->pass_ant_2 = $busuario['PASS_ANT_2'];
              $this->pass_ant_3 = $busuario['PASS_ANT_3'];
              $this->pass_ant_4 = $busuario['PASS_ANT_4'];
              $this->pass_ant_5 = $busuario['PASS_ANT_5'];
              $this->pass_ant_6 = $busuario['PASS_ANT_6'];
              $this->ult_acceso = $busuario['ULT_ACCESO'];
              $this->intentos = $busuario['INTENTOS'];
              $this->pass_provisorio = $busuario['PASS_PROVISORIO'];
              $this->activo_pass_provisorio = $busuario['ACTIVO_PASS_PROVISORIO'];
              mysqli_close($c);
              return true;
            }else {
              mysqli_close($c);
              return false;
            }
          }
          else
            {
              mysqli_close($c);
              return false;
            }
    }*/

    public function obtenerUnUsuarioPorId ($a) //obtener un usuario a partir de su ID
    {
      $consulta = "SELECT * FROM usuarios WHERE ID=$a";
      if ($busuario = MisFunciones::buscarEnBase($consulta))
            {
              $this->cargarDatosEnObjeto ($busuario);
              /*parent::setId($busuario['ID']);
              parent::setUsuario($busuario['USUARIO']);
              parent::setNom_ape($busuario['NOM_APE']);
              parent::setNivel(new Nivel($busuario['AUT_NIVEL']));
              parent::setVence_pass($busuario['VENCE_PASS']);
              parent::setEmail($busuario['EMAIL']);
              parent::setActivo($busuario['ACTIVO']);
              parent::setCumpleanio($busuario['CUMPLEANIO']);
              $this->contrasenia = $busuario['PASSWORD'];
              $this->pass_ant_1 = $busuario['PASS_ANT_1'];
              $this->pass_ant_2 = $busuario['PASS_ANT_2'];
              $this->pass_ant_3 = $busuario['PASS_ANT_3'];
              $this->pass_ant_4 = $busuario['PASS_ANT_4'];
              $this->pass_ant_5 = $busuario['PASS_ANT_5'];
              $this->pass_ant_6 = $busuario['PASS_ANT_6'];
              $this->ult_acceso = $busuario['ULT_ACCESO'];
              $this->intentos = $busuario['INTENTOS'];
              $this->pass_provisorio = $busuario['PASS_PROVISORIO'];
              $this->activo_pass_provisorio = $busuario['ACTIVO_PASS_PROVISORIO'];*/
              return true;
            }else {
              return false;
            }
    }

    public function obtenerUnUsuarioPorNombre ($nombre) //obtener un usuario a partir de su Nombre de Usuario
    {
      $consulta = "SELECT * FROM usuarios WHERE USUARIO = '$nombre'";
      if ($busuario = MisFunciones::buscarEnBase($consulta))
            {
              $this->cargarDatosEnObjeto ($busuario);
              return true;
            }else {
              return false;
            }
      /*$c = MisFunciones::abrirBase();
      if (!$c) {die("Lo siento, error en la conexión");}
          else
          {
            $consulta = "SELECT * FROM usuarios WHERE USUARIO = '$nombre'";
            return $this->obtenerUsuarioMysqliFetch($consulta, $c);
          }*/
    }

    private function contraseniaEsValida ($a) {
      if ($this->activo_pass_provisorio == 1) {
        if ($a == $this->pass_provisorio) {
          parent::setVence_pass(date("Y-n-j H:i:s"));
          return true;
        }else {
          return false;
          }
      }elseif ($a == $this->contrasenia) {
        return true;
        }else {
          return false;
        }
    }

    private function intentoFallido () {
      $this->intentos --;
      $consulta = "UPDATE usuarios SET INTENTOS = $this->intentos";
      if ($this->intentos <= 0) {
        parent::setActivo(0);
        $consulta = $consulta . ', ACTIVO = ' . parent::getActivo();
      }
      $consulta = $consulta . ' WHERE id = ' . parent::getId();
      if (MisFunciones::grabarEnBase($consulta)) {
        return 2;
      } else {return 5;}
    }

    private function intentoExitoso ($ahora) {
      $this->intentos = 5;
      $nId = parent::getId();
      $consulta = "UPDATE usuarios SET INTENTOS = $this->intentos, ULT_ACCESO = '$ahora' WHERE id = $nId";
      if (MisFunciones::grabarEnBase($consulta)) {
        return 1;
      } else {return 5;}
    }

    public function comprobarContrasenia($a) {
      // retornos 1 = OK, 2 = Error User o Pass, 3 = user block, 4 = Pass vencido, 5= ERROR en DB
      if ($this->contraseniaEsValida($a)) {
          if (parent::getActivo() == 1) {
            $ahora = date("Y-n-j H:i:s");
            $tiempo_transcurrido = strtotime(parent::getVence_pass())-strtotime($ahora);
            if ($tiempo_transcurrido > 0) {
                return $this->intentoExitoso($ahora);
            }else {return 4;}
          }else {return 3;}
        } else {return $this->intentoFallido();}
    }
}