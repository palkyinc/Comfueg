<?php

class Equipo
{
  private $id;
  private $nombre;
  private $num_dispositivo;
  private $mac_address;
  private $ip;
  private $num_antena;
  private $fecha_alta;
  private $fecha_baja;
  private $comentario;
  private $errores = null;
  private  const TABLA = 'equipos';

  public function __construct($dato = null)
  {
    if (is_array($dato)) {
          $this->setDatosPorArray($dato);
        } elseif (is_numeric($dato)) {
                $this->obtenerPorId($dato);
              }
  }

  public function obtenerPorId($datoId)
  {
    $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE id = :datoId');
      $stmt->bindParam (':datoId', $datoId, PDO::PARAM_INT );
      $stmt->execute();
              if ($stmt->rowCount() === 1)
                  {
                    $this->setDatosPorArray($stmt->fetch());
                    return (true);
                  }
                  else
                    {
                      return (false);
                    }
  }
  public function setDatosPorArray ($datoArray) {
      if (!$this->setId($datoArray['id'])) {
        $this->errores .= 'Error en el ID <br>';
      }
      if (!$this->setNombre($datoArray['nombre'])) {
        $this->errores .= 'Error en el Nombre <br>';
      }
      if (!$this->setNum_dispositivo($datoArray['num_dispositivo'])) {
        $this->errores .= 'Error en el Dispositivo <br>';
      }
      if (!$this->setMac_address($datoArray['mac_address'])) {
        $this->errores .= 'Error en el Mac Address <br>';
      }
      if (!$this->setIp($datoArray['ip'])) {
        $this->errores .= 'Error en el IP <br>';
      }
      if (!$this->setNum_antena($datoArray['num_antena'])) {
        $this->errores .= 'Error en la Antena <br>';
      }
      if (!$this->setFecha_alta($datoArray['fecha_alta'])) {
        $this->errores .= 'Error en la Fecha de Alta <br>';
      }
      if (!$this->setFecha_baja($datoArray['fecha_baja'])) {
        $this->errores .= 'Error en la Fecha de baja <br>';
      }
      if (!$this->setComentario($datoArray['comentario'])) {
        $this->errores .= 'Error en el Comentario <br>';
      }
      if ($this->errores !== null) {
        return false;
      }else {return true;}
    }

    public function getId () {
      return $this->id;
    }
    public function getNombre () {
      return $this->nombre;
    }
    public function getNum_dispositivo () {
      return $this->num_dispositivo;
    }
    public function getDispositvoModelo() {
      $dispositivo = new Dispositivo($this->num_dispositivo);
      return $dispositivo->getModelo();
  }
    public function getMac_address () {
      return $this->mac_address;
    }
    public function getIp () {
      return $this->ip;
    }
    public function getNum_antena () {
      return $this->num_antena;
    }
    public function getAntenaDescripcion() {
    $antena = new Antena($this->num_antena);
    return $antena->getDescripcion();
    }
    public function getFecha_alta () {
      return $this->fecha_alta;
    }
    public function getFecha_baja () {
      return $this->fecha_baja;
    }
    public function getComentario () {
      return $this->comentario;
    }
    public function getErrores () {
      return $this->errores;
    }
    public function setErrores () {
      $this->errores = null;
    }
    public function setId ($dato) {
      if ($dato === '' || filter_var($dato, FILTER_VALIDATE_INT)) {
        $this->id = $dato;
        return true;
      }else {
            return false;
            }
    }
    public function setNombre ($dato) {
      $dato = trim($dato);
      if (strlen($dato) < 3 || strlen($dato) > 45 ) {
        return false;
      }else {
        $this->nombre = $dato;
        return true;
        }
    }
    public function setNum_dispositivo ($dato) {
      if (filter_var($dato, FILTER_VALIDATE_INT)) {
        $dispositivo = new Dispositivo();
        if ($dispositivo->obtenerDispositivoPorId($dato)) {
          $this->num_dispositivo = $dato;
	        return true;
        }
      return false;
      }
    }
    public function setMac_address ($dato) {
        $dato = trim($dato);
        $existeElMac = self::getMacIgual($dato);
      if ((filter_var($dato, FILTER_VALIDATE_MAC) && !$existeElMac) || ($this->id === $existeElMac)) {
        $this->mac_address = $dato;
        return true;
      }else {
            return false;
            }
    }
    public function setIp ($dato) {
        $dato = trim($dato);
      if (filter_var($dato, FILTER_VALIDATE_IP)) {
        $this->ip = $dato;
        return true;
      }else {
            return false;
            }
    }
    public function setNum_antena ($dato) {
        $dato = trim($dato);
        $antena = new Antena();
    if ($antena->obtenerAntenaPorId($dato)) {
        $this->num_antena = $dato;
        return true;
      }else {
            return false;
            }
    }
    public function setFecha_alta ($dato = null) {
      if (!$this->id) {
        $this->fecha_alta = date("Y-m-d");
        return true;
      } elseif (MisFunciones::validarDate($dato))  {
                $this->fecha_alta = $dato;
                return true;
              }
      return true;
    }
    public function setFecha_baja ($dato = null) {
      if ($dato === 'baja') {
        $this->fecha_baja = date("Y-m-d");
        return true;
      } elseif (MisFunciones::validarDate($dato)) {
        $this->fecha_baja = $dato;
        return true;
      }
      return true;
    }
    public function setDesactivar () {
      $this->fecha_baja = date("Y-m-d");
      $this->guardarEnDb();
    }
    public function setActivar () {
      $this->fecha_alta = date("Y-m-d");
      $this->fecha_baja = null;
      $this->guardarEnDb();
    }
    public function setComentario ($dato) {
      $dato = trim($dato);
      if (strlen($dato) > 100 ) {
        return false;
      }else {
        $this->comentario = $dato;
        return true;
        }
    }
    public static function getCollectionEquipo ($query, $paginas) {
      $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (mac_address) LIKE upper (:query) LIMIT :paginas, 20";
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      //MisFunciones::imprimirConPrintR($stmt->errorInfo()); die;
      if ($stmt->rowCount() >= 1) {
         return $stmt->fetchAll(PDO::FETCH_CLASS, 'Equipo');
        }
      return false;
    }
    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA . ' SET nombre = :nombre, num_dispositivo = :num_dispositivo, mac_address = :mac_address, ip = :ip, num_antena = :num_antena, fecha_alta = :fecha_alta, fecha_baja = :fecha_baja, comentario = :comentario WHERE id = :id');
         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
         $stmt->bindParam(':num_dispositivo', $this->num_dispositivo, PDO::PARAM_INT);
         $stmt->bindParam(':mac_address', $this->mac_address, PDO::PARAM_STR);
         $stmt->bindParam(':ip', $this->ip, PDO::PARAM_STR);
         $stmt->bindParam(':num_antena', $this->num_antena, PDO::PARAM_INT);
         $stmt->bindParam(':fecha_alta', $this->fecha_alta, PDO::PARAM_STR);
         $stmt->bindParam(':fecha_baja', $this->fecha_baja, PDO::PARAM_STR);
         $stmt->bindParam(':comentario', $this->comentario, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA . ' (nombre, num_dispositivo, mac_address, ip, num_antena, fecha_alta, fecha_baja, comentario) VALUES(:nombre, :num_dispositivo, :mac_address, :ip, :num_antena, :fecha_alta, :fecha_baja, :comentario)');
      $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
      $stmt->bindParam(':num_dispositivo', $this->num_dispositivo, PDO::PARAM_INT);
      $stmt->bindParam(':mac_address', $this->mac_address, PDO::PARAM_STR);
      $stmt->bindParam(':ip', $this->ip, PDO::PARAM_STR);
      $stmt->bindParam(':num_antena', $this->num_antena, PDO::PARAM_INT);
      $stmt->bindParam(':fecha_alta', $this->fecha_alta, PDO::PARAM_STR);
      $stmt->bindParam(':fecha_baja', $this->fecha_baja, PDO::PARAM_STR);
      $stmt->bindParam(':comentario', $this->comentario, PDO::PARAM_STR);
      $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'Nombre -> ' . $this->getNombre() . '|',
                      'Num_Dispositivo -> ' . $this->getNum_Dispositivo()  . '|',
                      'Mac_address -> ' . $this->getMac_address()  . '|',
                      'Ip -> ' . $this->getIp()  . '|',
                      'Num_antena -> ' . $this->getNum_antena()  . '|',
                      'Fecha_alta -> ' . $this->getFecha_alta()  . '|',
                      'Fecha_baja -> ' . $this->getFecha_baja()  . '|',
                      'Comentario -> ' . $this->getComentario()  . '|',
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        return $resultado;
      } else return false;
    }
    private function obternerDiferencias () {
      $datoDB = new Equipo ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getNombre() != $datoDB->getNombre()) {
        $resultado ['changes'] .= 'Nombre->' . $datoDB->getNombre() . 'X' . $this->getNombre();
      }
      if ($this->getNum_dispositivo() != $datoDB->getNum_dispositivo()) {
        $resultado ['changes'] .= '|Num_dispositivo->' . $datoDB->getNum_dispositivo() . 'X' . $this->getNum_dispositivo();
      }
      if ($this->getMac_address() != $datoDB->getMac_address()) {
        $resultado ['changes'] .= '|Mac_address->' . $datoDB->getMac_address() . 'X' . $this->getMac_address();
      }
      if ($this->getIp() != $datoDB->getIp()) {
        $resultado ['changes'] .= '|Ip->' . $datoDB->getIp() . 'X' . $this->getIp();
      }
      if ($this->getNum_antena() != $datoDB->getNum_antena()) {
        $resultado ['changes'] .= '|Num_antena->' . $datoDB->getNum_antena() . 'X' . $this->getNum_antena();
      }
      if ($this->getFecha_alta() != $datoDB->getFecha_alta()) {
        $resultado ['changes'] .= '|Fecha_alta->' . $datoDB->getFecha_alta() . 'X' . $this->getFecha_alta();
      }
      if ($this->getFecha_baja() != $datoDB->getFecha_baja()) {
        $resultado ['changes'] .= '|Fecha_baja->' . $datoDB->getFecha_baja() . 'X' . $this->getFecha_baja();
      }
      if ($this->getComentario() != $datoDB->getComentario()) {
        $resultado ['changes'] .= '|Comentario->' . $datoDB->getComentario() . 'X' . $this->getComentario();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      $where = '';
      if ($query != '') {
        $where = "  WHERE upper (mac_address) LIKE upper (:query)";
        $query = '%'.$query.'%';
        }
      $consulta = 'SELECT id FROM ' . self::TABLA . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
    public static function getMacIgual($query)
    {
        $where = "  WHERE upper (mac_address) = upper (:query)";
        $consulta = 'SELECT id FROM ' . self::TABLA . $where;
        $stmt = MyPdo::getStatement($consulta);
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() === 1) {
              return ($stmt->fetch()['id']);
            } else {
              return (false);
            }
    }
}// fin de la clase