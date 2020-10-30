<?php

spl_autoload_register(function ($nombre_clase) {
  require_once $nombre_clase . '.Class.php';
});

abstract class MisFunciones {

  public static function buscarEnBase ($consulta) { //$bdato = MisFunciones::buscarEnBase($consulta)
    $linkdb = self::abrirBase();
      if (!$linkdb) die("Lo siento, error en la conexión");
          else
          {
            if ($resultado = mysqli_query($linkdb, $consulta))
                {
                  if ($bdato = mysqli_fetch_array($resultado))
                    {
                    mysqli_close($linkdb);
                    return ($bdato);
                    }else return (false);
                }
                else
                  {
                    mysqli_close($linkdb);
                    return (false);
                  }
          }
    }

    public static function buscarEnBaseArray ($consulta) { //$bdato = MisFunciones::buscarEnBase($consulta)
    $linkdb = self::abrirBase();
      if (!$linkdb) die("Lo siento, error en la conexión");
          else
          {
            if ($resultado = mysqli_query($linkdb, $consulta))
                {
                  while ($adato = mysqli_fetch_array($resultado))
                    {
                      $bdato [] = $adato;
                    }
                    mysqli_close($linkdb);
                    return ($bdato);
                }
                else
                  {
                    mysqli_close($linkdb);
                    return (false);
                  }
          }
    } 

    public static function abrirBase(){
        $linkdb= mysqli_connect("127.0.0.1", "phpuser", "palito", "slam");
        return ($linkdb);
    }  

    public static function grabarEnBase ($consulta) {
      $linkdb = self::abrirBase();
      if (!$linkdb) echo "Lo siento, error en la conexión a MySQL";
      else {
        $resultado = mysqli_query($linkdb, $consulta);
        mysqli_close($linkdb);
        if ($resultado) {
          return (true);
        }
        else return (false);
      }
    }

    public static function getObjetoData($objeto, $tabla, $where){
      $linkdb = self::abrirBase();
      if (!$linkdb) die("Lo siento, error en la conexión");
         else
         {
           if ($where != "") $consulta = "SELECT id FROM $tabla $where";
              else $consulta = "SELECT id FROM $tabla";
            //echo $consulta;
           if ($resultado = mysqli_query($linkdb, $consulta))
               {
                      if (($uDato = mysqli_fetch_array($resultado)) == null) return (null);
                        $aDato[] = new $objeto($uDato['id']);
                        while ($uDato = mysqli_fetch_array($resultado))
                        {
                          $aDato[] = new $objeto($uDato['id']);
                        }
                        return ($aDato);
                }
          }
    }

    public static function obtenerUsuarioLogeado () {
      return (self::obtenerDatoSesion(1));
      }

    public static function obtenerDatoSesion($a) { // $a -> 0= nivel; 1= idUsuario;
      if (!isset($_SESSION)) {
        session_name("loginUsuario");
        session_start();
      }
      if (isset($_SESSION["autentificado"]))
      {
          if ($_SESSION["autentificado"] != "SI")
              {
              return (0); //header("Location: index.html");
              }
              else
                  {
                    $fechaGuardada = $_SESSION["ultimoAcceso"];
                    $ahora = date("Y-n-j H:i:s");
                    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
                     if($tiempo_transcurrido >= 600)
                         {
                         session_destroy(); // destruyo la sesión
                         return (0); //header("Location: index.html");
                         }
                        else
                            {
                              $_SESSION["ultimoAcceso"] = $ahora;
                              if ($a == 1) return ($_SESSION["idUsuario"]);
                                else return ($_SESSION["nivel"]);
                            }
                }
      }
    }

    public static function seguridad () {
      return (self::obtenerDatoSesion(0));
    }

    /*public static function grabarAudits ( $consulta) {
      $barra = '';
      $aArray = (explode(' ', $consulta));
      foreach ($aArray as $key => $value) {
        if (substr($value,0,1) == "'") {
          $value = substr($value,1);
        }
        while (substr($value,-1) == "," || substr($value,-1) == "'"){
          if (substr($value,-1) == ",") {
            $barra = '|';
          }
          $value = substr ($value,0, strlen($value)-1);
        }
        if ($value === '=') {
          $value = '->';
        }
        $value .= $barra;
        $aArray[$key] = $value;
      }
      if ($aArray[0] == "UPDATE"){
            $item_auditable = $aArray[1];
            $i=2;
            $changes = "";
            while ($aArray[$i] != "WHERE") {
            $changes = $changes . $aArray[$i] . " ";
            $i++;
          }
          $id_auditable= $aArray[$i + 3];
      }
      if ($aArray[0] == "INSERT") {
        $item_auditable = $aArray[2];
        $i=3;
        $changes = "";
        while ( $i < count($aArray)) {
        $changes = $changes . $aArray[$i] . " ";
        $i++;
        }
        $id_auditable = 0;//$dato;
      }
      //self::imprimirConPrintR($aArray);
      //die;
      $action = $aArray[0];
      $id_usuario = self::obtenerUsuarioLogeado();
      //$consulta = "INSERT INTO audits SET id_usuario = $id_usuario, id_auditable = $id_auditable, item_auditable = '$item_auditable', action = '$action', changes = '$changes', fecha = NOW()";
      //echo $consulta;
      //die;
      if (self::grabarEnBase($consulta)) return (true);
        else return (false);
      //echo $consulta;
    }*/

    public static function grabarAuditsPDO ($audits){
      /*
      **  $audit = [
      **            'id_usuario' =>     id usuario haciendo el cambio
      **            'id_auditable' =>   id elemento a auditar
      **            'item_auditable' => nombre de la tabla
      **            'action' =>         NUEVO o MODIFICA
      **            'changes' =>        campo -> nuevo valor | campo -> nuevo valor
      **            ];
      */ 
        $consulta = 'INSERT INTO audits (id_usuario, id_auditable, item_auditable, action, changes, fecha) VALUES (:id_usuario, :id_auditable, :item_auditable, :action, :changes, NOW())';
        $stmt = MyPdo::getStatement($consulta);
        $stmt->bindParam(':id_usuario', $audits['id_usuario'], PDO::PARAM_INT);
        $stmt->bindParam(':id_auditable', $audits['id_auditable'], PDO::PARAM_INT);
        $stmt->bindParam(':item_auditable', $audits['item_auditable'], PDO::PARAM_STR, 12);
        $stmt->bindParam(':action', $audits['action'], PDO::PARAM_STR, 12);
        $stmt->bindParam(':changes', $audits['changes'], PDO::PARAM_STR, 12);
        $stmt->execute();
        //MisFunciones::imprimirConPrintR($stmt->errorinfo());die;
        if($stmt->rowCount() === 1) {
            return true;
          } else return false;
         
    }
      

    public static function imprimirConPrintR ($elemento) {
      echo "<pre>";
      echo '<br><br><br><br><br><br><hr>';
      print_r($elemento);
      echo "<hr></pre>";
      
    }
    public static function validarString($dato, $maximo, $minimo) {
      if ( !empty($dato)){
        $dato = trim($dato);
        if (strlen($dato) > $maximo || strlen($dato) < $minimo) {
        return false;
        } else return true;
      } return false;
    }
    public static function validarNivel ($id, $tabla) {
      $consulta = "SELECT id FROM $tabla where id = $id";
      if (self::buscarEnBase($consulta)) {// Si nivel existe en Base de Datos
      return true;
      }return false;
    }
    public static function validarEmail ($email) {
      $emailRegEx = '/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i';
      return preg_match($emailRegEx, $email);
      //return true;
    }
    public static function validarBooleano ($dato) {
      if ($dato === "0" || $dato === "1" ) {
        return true;
      }else if (is_bool($dato)) {
                return true;
            } else {return false;}
    }
    public static function validarDate ($dato) {
      $fechaRegEx = '/\d\d\d\d-\d\d-\d\d/';
      if ( strlen($dato) === 10) {
          return preg_match($fechaRegEx, $dato);
        } else {return false;}
    }

    public static function mandarEmail($to,$subject,$body)
    {
        require_once "Mail.php";

             $from = 'comunicacionesfueguinas@gmail.com'; //change this to your email address
             //$to = 'migvicpereyra@gmail.com'; // change to address
             //$subject = 'SLAM subject here'; // subject of mail
             //$body = "Hello world! this is a SLAM content of the email"; //content of mail

             $headers = array
                    (
                        'From' => $from,
                        'To' => $to,
                        'Subject' => $subject
                    );

             $smtp = Mail::factory('smtp', array(
                     'host' => 'ssl://smtp.gmail.com',
                     'port' => '465',
                     'auth' => true,
                     'username' => 'comunicacionesfueguinas@gmail.com', //your gmail account
                     'password' => 'S0l0Sequre' // your password
                 ));

             // Send the mail
             $mail = $smtp->send($to, $headers, $body);

             //check mail sent=False or not=True
             return (PEAR::isError($mail));
    }
    
    public static function validarSesion () {
      $datosSesion = self::obtenerSerializado();
      //MisFunciones::imprimirConPrintR($datosSesion);
      if ($datosSesion->getAutenticado() === 0) {
        $datosSesion->noAutenticado();
      }elseif ($datosSesion->sesionExpiro()) {
            session_destroy(); // destruyo la sesión
            }else {
                  $datosSesion->autenticado();
                  }

      return $datosSesion;
    }

    public static function obtenerSerializado () {
      if (!isset($_SESSION)) {
        session_name("loginUsuario");
        session_start();
      }
      if (!isset($_SESSION['sesion_serializada'])) {
          $datosSesion = new Referencia();
      } else {
          $datosSesion = unserialize($_SESSION['sesion_serializada']);
      }
      return $datosSesion;
    }
    
    public static function guardarSesion ($datosSesion) {
      if (!isset($_SESSION)) {
        session_name("loginUsuario");
        session_start();
      }
      $_SESSION['sesion_serializada'] = serialize($datosSesion);
    }
    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
    }
}// fin de la clase