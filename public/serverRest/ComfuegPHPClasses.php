<?php


// FUNCIONES FUERA DEL STATIC
/*
function abrirBase(){
    $c= mysqli_connect("127.0.0.1", "phpuser", "palito", "slam");
    return ($c);
}

function grabarEnBase ($consulta) {
  $c = abrirBase();
  if (!$c) echo "Lo siento, error en la conexión a MySQL";
  else {
    $resultado = mysqli_query($c, $consulta);
    mysqli_close($c);
    if ($resultado) {
      return (true);
    }
    else return (false);
  }
}

function getObjetoData($objeto, $tabla, $where){
  $c = abrirBase();
   if (!$c) die("Lo siento, error en la conexión");
       else
       {
         if ($where != "") $consulta = "SELECT id FROM $tabla $where";
            else $consulta = "SELECT id FROM $tabla";
          //echo $consulta;
         if ($resultado = mysqli_query($c, $consulta))
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

function obtenerUsuarioLogeado () {
  return (obtenerDatoSesion(1));
  }

function obtenerDatoSesion($a) { // $a -> 0= nivel; 1= idUsuario;
  if (!isset($_SESSION)) {
    session_name("loginUsuario");
    session_start();
  }
  if (isset($_SESSION["autentificado"]))
  {
      if ($_SESSION["autentificado"] != "SI")
          {
          echo 'entre';
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
  }else return (0);
}

function seguridad () {
  return (obtenerDatoSesion(1));
}

function grabarAudits ( $a, $b ) {
  //echo $a. "<br>";
  $aArray = (explode(' ', $a));
  foreach ($aArray as $key => $value) {
    if (substr($value,0,1) == "'") {
      $value = substr($value,1);
    }
    while (substr($value,-1) == "," || substr($value,-1) == "'"){
      $value = substr ($value,0, strlen($value)-1);
    }
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
  $id_auditable= $b;
  }
  $action = $aArray[0];
  $id_usuario = obtenerUsuarioLogeado();
  $consulta = "INSERT INTO audits SET id_usuario = $id_usuario, id_auditable = $id_auditable, item_auditable = '$item_auditable', action = '$action', changes = '$changes', fecha = NOW()";
  if (grabarEnBase($consulta)) return (true);
    else return (false);
  //echo $consulta;
}


// A PARTIR DE ACA SON VIEJAS!!

class Panel
{
    // $rol = PANEL, PTP_AP o PTP_ST
    var $num_panel,$nombre,$ssid, $rol, $id_equipo, $num_site, $panel_ant, $activo, $comentarios, $usuario, $contraseña;
    // constructor new Panel ($a); /$a -> id del panel
    function __construct($a)
    {
        $this->num_panel = $a;
        $this->ssid = "";
        $this->rol = "";
        $this->id_equipo = ""; // $objeto equipo
        $this->num_site = ""; // $objeto site
        $this->panel_ant = ""; // $objeto pnael
        $this->activo = "";
        $this->comentarios = "";
        $this->usuario = "";
        $this->password = "";
        $this->cobertura = "";
        obtenerPanelPorID($this->num_panel);
    }
    // implementación de métodos
    function obtenerPanelPorId($a) //$c puntero a MySQL
    {
     $c = abrirBase();
      if (!$c) die("Lo siento, error en la conexión");
          else
          {
            $consulta = "SELECT * FROM panel WHERE NUM_PANEL=$a";
            if ($resultado = mysqli_query($c, $consulta))
                {
                  if ($uDato = mysqli_fetch_array($resultado))
                    {
                    $this->num_panel = $uDato['NUM_PANEL'];
                    $this->ssid = $uDato['SSID'];
                    $this->rol = $uDato['ROL'];
                    $this->id_equipo = new Equipo ($uDato['ID_EQUIPO']);
                    $this->num_site = new Site ($uDato['NUM_SITE']);
                    if ($uDato['PANEL_ANT'] == null) $this->panel_ant = $uDato['PANEL_ANT']; else $this->panel_ant = new Panel ($uDato['PANEL_ANT']);
                    if ($uDato['ACTIVO'] == 1) $this->activo = 'Si'; else $this->activo = 'No';
                    $this->comentarios = $uDato['COMENTARIOS'];
                    $this->usuario = $uDato['USUARIO'];
                    $this->password = $uDato['PASSWORD'];
                    $this->cobertura = $uDato['COBERTURA'];
                    mysqli_close($c);
                    return (true);
                    }else echo ("ERROR en ID PANEL");
                }
                else
                  {
                    mysqli_close($c);
                    Return (false);
                  }
          }
    }
}
//DONE
class Equipo
{
    var $nun_equipo, $nombre, $num_device, $mac_address,$ip1,$ip2,$ip3,$ip4,$num_antena,$fecha_alta,$fecha_baja,$comentario;
    // constructor new Equipo ("","","","","","","","","","","","");
    function __construct($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l)
    {
        $this->num_equipo = $a;
        $this->nombre = $b;
        $this->num_device = $c;
        $this->mac_address = $d;
        $this->ip1 = $e;
        $this->ip2 = $f;
        $this->ip3 = $g;
        $this->ip4 = $h;
        $this->num_antena = $i;
        $this->fecha_alta = $j;
        $this->fecha_baja = $k;
        $this->comentario = $l;
    }
    // implementación de métodos
    function obtenerEquipoPorId($a)
    {
      $c = abrirBase();
      if (!$c) die("Lo siento, error en la conexión");
          else
          {
            $consulta = "SELECT * FROM equipo WHERE NUM_EQUIPO=$a";
            if ($resultado = mysqli_query($c, $consulta))
                {
                  if ($bdato = mysqli_fetch_array($resultado))
                    {
                    $this->num_equipo = $bdato['NUM_EQUIPO'];
                    $this->nombre = $bdato['NOMBRE'];
                    $this->num_device = $bdato['NUM_DEVICE'];
                    $this->mac_address = $bdato['MAC_ADDRESS'];
                    $this->ip1 = $bdato['IP1'];
                    $this->ip2 = $bdato['IP2'];
                    $this->ip3 = $bdato['IP3'];
                    $this->ip4 = $bdato['IP4'];
                    $this->num_antena = $bdato['NUM_ANTENA'];
                    $this->fecha_alta = $bdato['FECHA_ALTA'];
                    $this->fecha_baja = $bdato['FECHA_BAJA'];
                    $this->comentario = $bdato['COMENTARIO'];
                    mysqli_close($c);
                    return (true);
                    }else echo ("ERROR en ID Equipo");
                }
                else
                  {
                    mysqli_close($c);
                    Return (false);
                  }
          }
    }
    function mostrarDevice($c) //$c es el puntero de MySQL
    {
      $consulta = "SELECT * FROM device WHERE NUM_DEVICE = $this->num_device";
      $resultado = mysqli_query($c, $consulta);
      if ($resultado)
        {
          $uDevice = mysqli_fetch_array($resultado);
          return ($uDevice['MODELO']);
        }
    }

    function mostrarAntena($c) //$c es el puntero de MySQL
    {
      $consulta = "SELECT * FROM antena WHERE NUM_ANTENA = $this->num_antena";
      $resultado = mysqli_query($c, $consulta);
      if ($resultado)
        {
          $uAntena = mysqli_fetch_array($resultado);
          return ($uAntena['DESCRIPCION']);
        }
    }
}

class Cliente
{
    var $numCliente, $apellido, $nombre,$cod_area_tel,$telefono,$cod_area_cel,$celular,$email,$hashEmail,$emailVerificate;
    // constructor new Cliente("","","","","","","","","",""); new Cliente($numCliente, $apellido, $nombre,$cod_area_tel,$telefono,$cod_area_cel,$celular,$email,$hashEmail,$emailVerificate);
    function __construct($a,$b,$c,$d,$e,$f,$g,$h,$i,$j)
    {
        $this->numCliente = $a;
        $this->apellido = $b;
        $this->nombre = $c;
        $this->cod_area_tel = $d;
        $this->telefono = $e;
        $this->cod_area_cel = $f;
        $this->celular = $g;
        $this->email = $h;
        $this->hashEmail = $i;
        $this->emailVerificate = $j;
    }
    // implementación de métodos
    function mostrarCliente()
        {
        echo "<p>$this->nombre</p>";
        echo "<p>$this->apellido</p>";
        echo "<p>$this->numCliente</p>";
        }
    function obtenerUnClientePorId ($a) //obtener un usuario a partir de su ID
        {
          //require 'myLibrary.php';
          $c = abrirBase();
          if (!$c) die("Lo siento, error en la conexión");
              else
              {
                $consulta = "SELECT * FROM cliente WHERE NUM_CLIENTE=$a";
                $resultado = mysqli_query($c, $consulta);
                if ($resultado)
                    {
                      $bdato = mysqli_fetch_array($resultado);
                      if ($bdato)
                        {
                        $this->numCliente = $bdato['NUM_CLIENTE'];
                        $this->apellido = $bdato['APELLIDO'];
                        $this->nombre = $bdato['NOMBRE'];
                        $this->cod_area_tel = $bdato['COD_AREA_TEL'];
                        $this->telefono = $bdato['TELEFONO'];
                        $this->cod_area_cel = $bdato['COD_AREA_CEL'];
                        $this->celular = $bdato['CELULAR'];
                        $this->email = $bdato['EMAIL'];
                        $this->hashEmail = $bdato['HASH_EMAIL'];
                        $this->emailVerificate = $bdato['EMAIL_VERIFICATE'];
                        mysqli_close($c);
                        return (true);
                      }else echo ("ERROR en el busuario");
                    }
                    else
                      {
                        mysqli_close($c);
                        Return (false);
                      }
              }
      }
}

abstract class Prueba
{
    private $fecha,$ip,$mac,$firmware,$equipo,$ssid,$senial,$ruido,$ccq,$tx,$rx,$lanConectado,$lanVelocidad,$pingWisproLoss,$pingWisproAvg,$contactado;
}

class PruebaPanel extends Prueba
{
        private $cliConectados, $canal, $usoCpu,$memLibr, $panel;

        function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $q, $r, $s)
        {
            $this->fecha = $a;
            $this->panel = $b;
            $this->mac = $c;
            $this->firmware = $d;
            $this->equipo = $e;
            $this->cliConectados = $f;
            $this->ssid = $g;
            $this->canal = $h;
            $this->senial = $i;
            $this->ruido = $j;
            $this->ccq = $k;
            $this->usoCpu = $l;
            $this->memLibr = $m;
            $this->tx = $n;
            $this->rx = $o;
            $this->lanConectado = $p;
            $this->lanVelocidad = $q;
            $this->pingWisproLoss = $r;
            $this->pingWisproAvg = $s;
        }
        // implementación de métodos
    public function mostrarPruebaPanel()
    {
        echo "<p>Fecha: $this->fecha</p>";
        echo "<p>Panel: $this->panel</p>";
        echo "<p>MacAdress: $this->mac</p>";
        echo "<p>Firmware: $this->firmware</p>";
        echo "<p>Equipo: $this->equipo</p>";
        echo "<p>Clientes Conectados: $this->cliConectados</p>";
        echo "<p>SSID: $this->ssid</p>";
        echo "<p>Canal: $this->canal</p>";
        echo "<p>Señal: $this->senial</p>";
        echo "<p>Ruido: $this->ruido</p>";
        echo "<p>CCQ: $this->ccq</p>";
        echo "<p>Uso Del CPU: $this->usoCpu %</p>";
        echo "<p>Memoria Libre: $this->memLibr </p>";
        echo "<p>TX: $this->tx</p>";
        echo "<p>RX: $this->rx</p>";
        echo "<p>LanConectado: $this->lanConectado</p>";
        echo "<p>LanVelocidad: $this->lanVelocidad</p>";
        echo "<p>PingWisproLoss: $this->pingWisproLoss</p>";
        echo "<p>PingWisproAvg: $this->pingWisproAvg</p>";
    }
     public function mostrarPruebaPanelEv()
    {
        echo "<p>Fecha: $this->fecha</p>";
        echo "<p>Panel: $this->panel</p>";
        echo "<p>MacAdress: $this->mac</p>";
        echo "<p>Firmware: $this->firmware</p>";
        echo "<p>Equipo: $this->equipo</p>";
        if($this->cliConectados < 26) echo str_replace("\'","<p class=\"joya\">","\'Clientes Conectados: $this->cliConectados OK</p>");
            elseif ($this->cliConectados > 30) echo str_replace("\'","<p class=\"peligro\">","\'Clientes Conectados: $this->cliConectados ATENCIÓN: Demasiados Clientes en este PANEL.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'Clientes Conectados: $this->cliConectados REGULAR</p>");
        echo "<p>SSID: $this->ssid</p>";
        echo "<p>Canal: $this->canal</p>";
        echo "<p>Señal: $this->senial</p>";
        echo "<p>Ruido: $this->ruido</p>";
        if($this->ccq == -1) echo str_replace("\'","<p class=\"joya\">","\'CCQ: No se mide por ser 5AC.</p>");
        elseif($this->ccq > 900)
            echo str_replace("\'","<p class=\"joya\">","\'CCQ: $this->ccq OK</p>");
            elseif ($this->ccq < 700)
                echo str_replace("\'","<p class=\"peligro\">","\'CCQ: $this->ccq ATENCIÓN: Posible interferencia en canal, analizar cambio de canal.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'CCQ: $this->ccq REGULAR</p>");
        if ($this->usoCpu > 85)
            echo str_replace("\'","<p class=\"peligro\">","\'Uso Del CPU: $this->usoCpu ATENCIÓN: Equipo con sobrecarga de procesos.</p>");
            else echo str_replace("\'","<p class=\"joya\">","\'Uso Del CPU: $this->usoCpu OK</p>");
        if ($this->memLibr < 15)
            echo str_replace("\'","<p class=\"peligro\">","\'Memoria Libre: $this->memLibr % ATENCIÓN: Poca Memoria Libre.</p>");
            else echo str_replace("\'","<p class=\"joya\">","\'Memoria Libre: $this->memLibr % OK</p>");
        if($this->tx > 51) echo str_replace("\'","<p class=\"joya\">","\'TX: $this->tx OK</p>");
            elseif ($this->tx < 14) echo str_replace("\'","<p class=\"peligro\">","\'TX: $this->tx ATENCIÓN: Posible problema con antena de Panel.</p>");
            else echo str_replace("\'","<p class=\"atencion\">","\'TX: $this->tx REGULAR</p>");
        if($this->rx > 51)echo str_replace("\'","<p class=\"joya\">","\'RX: $this->rx OK</p>");
            elseif ($this->rx < 14) echo str_replace("\'","<p class=\"peligro\">","\'RX: $this->rx ATENCIÓN: Posible problema con antena de Panel.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'RX: $this->rx REGULAR</p>");
        if($this->lanConectado == 1) echo str_replace("\'","<p class=\"joya\">","\'LanConectado: Cable LAN conectado OK</p>");
            else echo str_replace("\'","<p class=\"peligro\">","\'LanConectado: ATENCIÓN: Cable LAN Desconectado.</p>");
        if($this->lanVelocidad == 100 || $this->lanVelocidad == 1000) echo str_replace("\'","<p class=\"joya\">","\'LanVelocidad: $this->lanVelocidad OK</p>");
            else echo str_replace("\'","<p class=\"peligro\">","\'LanVelocidad: $this->lanVelocidad ATENCIÓN: Reiniciar equipo y volver a chequear.</p>");
        if($this->pingWisproLoss < 20) echo str_replace("\'","<p class=\"joya\">","\'PingWisproLoss: $this->pingWisproLoss OK</p>");
            elseif ($this->pingWisproLoss > 70) echo str_replace("\'","<p class=\"peligro\">","\'PingWisproLoss: $this->pingWisproLoss ATENCIÓN: Se deben revisar enlaces PTP.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'PingWisproLoss: $this->pingWisproLoss REGULAR</p>");
        if($this->pingWisproAvg < 5) echo str_replace("\'","<p class=\"joya\">","\'PingWisproAvg: $this->pingWisproAvg mseg OK</p>");
            elseif ($this->pingWisproAvg > 15) echo str_replace("\'","<p class=\"peligro\">","\'PingWisproAvg: $this->pingWisproAvg mseg. ATENCIÓN: Se deben revisar 1)PTP. 2)Ancho de banda completamente usado. 3)Uso del CPU</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'PingWisproAvg: $this->pingWisproAvg mseg. REGULAR</p>");
    }
}
class PruebaCliente extends Prueba
{
    private $nomDispositivo,$panel,$horizontal,$vertical,$ipLan,$ipWan,$pingGoogleLoss,$pingGoogleAvg;
    // constructor
    function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $q, $r, $s, $t, $u, $v)
    {
        $this->fecha = $a;
        $this->nomDispositivo = $b;
        $this->mac = $c;
        $this->firmware = $d;
        $this->equipo = $e;
        $this->panel = $f;
        $this->senial = $g;
        $this->horizontal = $h;
        $this->vertical = $i;
        $this->ruido = $j;
        $this->ccq = $k;
        $this->ipLan = $l;
        $this->ipWan = $m;
        $this->tx = $n;
        $this->rx = $o;
        $this->lanConectado = $p;
        $this->lanVelocidad = $q;
        $this->pingWisproLoss = $r;
        $this->pingWisproAvg = $s;
        $this->pingGoogleLoss = $t;
        $this->pingGoogleAvg = $u;
        $this->contactado = $v;
    }
    // implementación de métodos
    public function mostrarPruebaCliente()
    {
        echo "<p>Fecha: $this->fecha</p>";
        echo "<p>Cliente: $this->nomDispositivo</p>";
        echo "<p>MacAdress: $this->mac</p>";
        echo "<p>Firmware: $this->firmware</p>";
        echo "<p>Equipo: $this->equipo</p>";
        echo "<p>Panel: $this->panel</p>";
        echo "<p>Señal: $this->senial</p>";
        echo "<p>Horizontal: $this->horizontal</p>";
        echo "<p>Vertical: $this->vertical</p>";
        echo "<p>Ruido: $this->ruido</p>";
        echo "<p>CCQ: $this->ccq</p>";
        echo "<p>IpLan: $this->ipLan</p>";
        echo "<p>IpWan: $this->ipWan</p>";
        echo "<p>TX: $this->tx</p>";
        echo "<p>RX: $this->rx</p>";
        echo "<p>LanConectado: $this->lanConectado</p>";
        echo "<p>LanVelocidad: $this->lanVelocidad</p>";
        echo "<p>PingWisproLoss: $this->pingWisproLoss</p>";
        echo "<p>PingWisproAvg: $this->pingWisproAvg</p>";
        echo "<p>PingGoogleLoss: $this->pingGoogleLoss</p>";
        echo "<p>PingGoogleAvg: $this->pingGoogleAvg</p>";
    }
    public function mostrarPruebaClienteConEv()
    {
        echo "<p>Fecha: $this->fecha</p>";
        echo "<p>Cliente: $this->nomDispositivo</p>";
        echo "<p>MacAdress: $this->mac</p>";
        echo "<p>Firmware: $this->firmware</p>";
        echo "<p>Equipo: $this->equipo</p>";
        echo "<p>Panel: $this->panel</p>";
        if($this->senial > -70)
                echo str_replace("\'","<p class=\"joya\">","\'Señal: $this->senial OK</p>");
                elseif ($this->senial < -75)
                    echo str_replace("\'","<p class=\"peligro\">","\'Señal: $this->senial ATENCIÓN: Posible obstrucción/antena mal orientada.</p>");
                    else echo str_replace("\'","<p class=\"atencion\">","\'Señal: $this->senial REGULAR</p>");
        echo "<p>Horizontal: $this->horizontal</p>";
        echo "<p>Vertical: $this->vertical</p>";
        echo "<p>Ruido: $this->ruido</p>";
        if($this->ccq > 900)
            echo str_replace("\'","<p class=\"joya\">","\'CCQ: $this->ccq OK</p>");
            elseif ($this->ccq < 700)
                echo str_replace("\'","<p class=\"peligro\">","\'CCQ: $this->ccq ATENCIÓN: Posible obstrucción/antena mal orientada.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'CCQ: $this->ccq REGULAR</p>");
        echo "<p>IpLan: $this->ipLan</p>";
        echo "<p>IpWan: $this->ipWan</p>";
        if($this->tx > 51)
            echo str_replace("\'","<p class=\"joya\">","\'TX: $this->tx OK</p>");
            elseif ($this->tx < 14)
                echo str_replace("\'","<p class=\"peligro\">","\'TX: $this->tx ATENCIÓN: Posible obstrucción/antena mal orientada.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'TX: $this->tx REGULAR</p>");
        if($this->rx > 51)
            echo str_replace("\'","<p class=\"joya\">","\'RX: $this->rx OK</p>");
            elseif ($this->rx < 14)
                echo str_replace("\'","<p class=\"peligro\">","\'RX: $this->rx ATENCIÓN: Posible obstrucción/antena mal orientada.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'RX: $this->rx REGULAR</p>");
        if($this->lanConectado == 1)
            echo str_replace("\'","<p class=\"joya\">","\'LAN Conectado: OK</p>");
                else echo str_replace("\'","<p class=\"peligro\">","\'LAN Conectado: Router DESCONECTADO.</p>");
        if($this->lanVelocidad == 100 || $this->lanVelocidad == 1000)
            echo str_replace("\'","<p class=\"joya\">","\'LanVelocidad: $this->lanVelocidad OK</p>");
                else echo str_replace("\'","<p class=\"peligro\">","\'LanVelocidad: $this->lanVelocidad ATENCIÓN: Posible problema en cable entre POE y Router.</p>");
        if($this->pingWisproLoss < 2)
            echo str_replace("\'","<p class=\"joya\">","\'PingWisproLoss: $this->pingWisproLoss% OK</p>");
            elseif ($this->pingWisproLoss > 5)
                            echo str_replace("\'","<p class=\"peligro\">","\'PingWisproLoss: $this->pingWisproLoss% ATENCIÓN: Se deben revisar enlaces PTP en caso de estar señal OK.</p>");
                    else echo str_replace("\'","<p class=\"atencion\">","\'PingWisproLoss: $this->pingWisproLoss% REGULAR</p>");
        if($this->pingWisproAvg < 20)
            echo str_replace("\'","<p class=\"joya\">","\'PingWisproAvg: $this->pingWisproAvg mseg OK</p>");
            elseif ($this->pingWisproAvg > 70)
                echo str_replace("\'","<p class=\"peligro\">","\'PingWisproAvg: $this->pingWisproAvg mseg ATENCIÓN: Se deben revisar enlaces PTP en caso de estar Señal OK.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'PingWisproAvg: $this->pingWisproAvg mseg REGULAR</p>");
        if($this->pingGoogleLoss < 2)
            echo str_replace("\'","<p class=\"joya\">","\'PingGoogleLoss: $this->pingGoogleLoss% OK</p>");
            elseif ($this->pingGoogleLoss > 5)
                echo str_replace("\'","<p class=\"peligro\">","\'PingGoogleLoss: $this->pingGoogleLoss% ATENCIÓN: Si Ping Wispro Loss = OK, Posiblemente:. 1:Cliente usando todo el ancho de Banda. 2:Cliente deshabilitado desde Wispro por Mora. 3:Problema con proveedores.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'PingGoogleLoss: $this->pingGoogleLoss% REGULAR</p>");
    if($this->pingGoogleAvg < 100)
            echo str_replace("\'","<p class=\"joya\">","\'PingGoogleAvg: $this->pingGoogleAvg mseg OK</p>");
            elseif ($this->pingGoogleAvg > 300)
                echo str_replace("\'","<p class=\"peligro\">","\'PingGoogleAvg: $this->pingGoogleAvg mseg ATENCIÓN: Si Ping Wispro AVG = OK. 1:Posiblemente cliente usando todo el ancho de Banda. 2:Posible problema con proveedores.</p>");
                else echo str_replace("\'","<p class=\"atencion\">","\'PingGoogleAvg: $this->pingGoogleAvg mseg REGULAR</p>");
    }
}

class Velocidad
{
    private $nomVelocidad, $bajada, $subida;
    // constructor
    function __construct($n, $b, $s)
    {
        $this->nomVelocidad = $n;
        $this->bajada = $b;
        $this->subida = $s;
    }
    // implementación de métodos
    public function mostrarVelocidad()
    {
        echo "<p>Nombre: $this->nomVelocidad</p>";
        echo "<p>Bajada: $this->bajada</p>";
        echo "<p>Subida: $this->subida</p>";
    }

}


class contrato
{
    var     $ip,
            $numCliente,
            $velocidad,
            $equipo,
            $macaddress,
            $ubicacionGeo,
            $comentarios,
            $panel,
            $status;

    // constructor
    function __construct($i,$n,$v,$e,$m,$u,$c,$p,$s)
    {
        $this->ip = $i;
        $this->numCliente = $n;
        $this->velocidad = $v;
        $this->equipo = $e;
        $this->macaddress = $m;
        $this->ubicacionGeo = $u;
        $this->comentarios = $c;
        $this->panel = $p;
        $this->status = $s;
    }
    // implementación de métodos
    function mostrarContrato()
    {
        echo "$ip";
        echo "$numCliente";
        echo "$velocidad";
        echo "$equipo";
        echo "$macaddress";
        echo "$ubicacionGeo";
        echo "$comentarios";
        echo "$panel";
        echo "$status";
    }

}
?>
*/
