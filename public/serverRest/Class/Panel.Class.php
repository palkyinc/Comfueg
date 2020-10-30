<?php

class Panel
{
  private $id;
  private $ssid;
  private $rol; // 'PTPAP', 'PTPST', 'PANEL'
  private $id_equipo;
  private $num_site;
  private $panel_ant;
  private $activo;
  private $cobertura;
  private $comentario;
  private $errores = null;
  private const TABLA = 'paneles';
  private const CARPETA = 'imgUsuarios/';
  private const ROLES = [ 'PTPAP' => 'PTPAP', 'PTPST' => 'PTPST', 'PANEL' =>'PANEL', 'SWITCH' => 'SWITCH'];
  private const RANGO_IP_PANELES = '10.10.0.%';
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
      $stmt->bindParam (':datoId', $datoId, PDO::PARAM_INT);
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
      if (!$this->setSsid($datoArray['ssid'])) {
        $this->errores .= 'Error en el SSID <br>';
      }
      if (!$this->setRol($datoArray['rol'])) {
        $this->errores .= 'Error en el Rol <br>';
      }
      if (!$this->setId_equipo($datoArray['id_equipo'])) {
          $this->errores .= 'Error en el Equipo <br>';
      }
      if (!$this->setNum_site($datoArray['num_site'])) {
          $this->errores .= 'Error en el Sitio <br>';
      }
      if (!$this->setPanel_ant($datoArray['panel_ant'])) {
          $this->errores .= 'Error en el Panel anterior <br>';
      }
      if (!$this->setActivo($datoArray['activo'])) {
          $this->errores .= 'Error en el Activo <br>';
      }
      if (!$this->setComentario($datoArray['comentario'])) {
          $this->errores .= 'Error en el Comentario <br>';
      }
      if (!$this->setCobertura($datoArray['cobertura'])) {
          $this->errores .= 'Error en el Cobertura <br>';
      }
      if ($this->errores !== null) {
        return false;
      }else {return true;}
    }

    public function getId () {
      return $this->id;
    }
    public function getSsid () {
      return $this->ssid;
    }
    public function getRol () {
      return $this->rol;
    }
    public function getId_equipo () {
      return $this->id_equipo;
    }
    public function getEquipoNombre($dato = null) {
      $equipo = new Equipo($this->id_equipo);
        if ($dato === null || ($equipo->getFecha_baja() === null && $dato === 1)){
        return $equipo->getNombre();
        } elseif ($equipo->getFecha_baja() !== null && $dato === 1) {
          return '<div class="alert alert-danger" role="alert">
                  ' . $equipo->getNombre() . '
                  </div>';
        }
      return false;
    }
    public function getNum_site () {
      return $this->num_site;
    }
    public function getSiteNombre()
    {
      $site = new Site($this->num_site);
      return $site->getNombre();
    }
    public function getPanel_ant () {
      return $this->panel_ant;
    }
    public function getPanelAntEquipo($dato = null) {
      if (!$this->panel_ant) {
        return 'Wispro';
      } else {
          $panel = new Panel($this->panel_ant);
          if ($dato === null || ($panel->getActivo() == 1 && $dato === 1)) {
        return $panel->getEquipoNombre();
          } elseif ($panel->getActivo() == 0 && $dato === 1) {
            return '<div class="alert alert-danger" role="alert">
                        ' . $panel->getEquipoNombre() . '
                        </div>';
          }
      }  
    return false;
     }
    public function getActivo () {
      return $this->activo;
    }
    public function getComentario () {
      return $this->comentario;
    }
    public function getCobertura () {
      return $this->cobertura;
    }
    public function getRoles () {
      return self::ROLES;
    }
    public function getErrores () {
      return $this->errores;
    }
    public function setErrores ($error = null) {
      $this->errores = $error;
    }
    public function setId ($dato) {
      if ($dato === '' || filter_var($dato, FILTER_VALIDATE_INT)) {
        $this->id = $dato;
        return true;
      }else {
            return false;
            }
    }
    public static function getCarpeta() {
      return self::CARPETA;
    }
    public function setSsid ($dato) {
      $dato = trim($dato);
      if (strlen($dato) < 3 || strlen($dato) > 15 ) {
        return false;
      }else {
        $this->ssid = $dato;
        return true;
        }
    }
    public function setRol ($dato) { // 'PTPAP', 'PTPST', 'PANEL'
        $dato = trim($dato);
        if ($dato === 'PTPAP' || $dato === 'PTPST' || $dato === 'PANEL' ) {
            $this->rol = $dato;
            return true;
        } else {
            return false;
        }
    }
    public function setId_equipo ($dato) {
        $dato = trim($dato);
        $existeElEquipo = self::existeEquipoInsertado($dato);
        if ((filter_var($dato, FILTER_VALIDATE_INT) && !$existeElEquipo) || ($this->id === $existeElEquipo)) {
                $equipo = new Equipo();
                if ($equipo->obtenerPorId($dato)) {
                    $this->id_equipo = $dato;
                    return true;
                }
        return false;
        }
    }
    public function setNum_site ($dato) {
        $dato = trim($dato);
        $site = new Site();
        if ($site->obtenerPorId($dato)) {
            $this->num_site = $dato;
            return true;
        } else {
            return false;
        }
    }
    public function setPanel_ant ($dato) {
        $dato = trim($dato);
        if ($dato === ""){
          $this->panel_ant = null;
          return true;  
        } else {
              $panel = new Panel();
              if ($panel->obtenerPorId($dato)) {
                  $this->panel_ant = $dato;
                  return true;
                }else {
                      return false;
                      }
              }
    }
    public function setActivo ($dato = null) {
      if ($this->id == '') {
        $this->activo = 1;
        return true;
      } elseif ($dato == 0 || $dato == 1) {
            $this->activo = $dato;
            return true;
          }
      return false;
    }
    public function setDesactivar () {
      $this->activo = 0;
      $this->guardarEnDb();
    }
    public function setActivar () {
      $this->activo = 1;
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
    public function setCobertura ($dato) {
        $this->cobertura = $dato;
        return true;
    }
    public static function getCollectionPanel ($query, $paginas) {
      $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (ssid) LIKE upper (:query) LIMIT :paginas, 20";
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() >= 1) {
         return $stmt->fetchAll(PDO::FETCH_CLASS, 'Panel');
        }
      return false;
    }

    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA . ' SET ssid = :ssid, rol = :rol, id_equipo = :id_equipo, num_site = :num_site, panel_ant = :panel_ant, activo = :activo, cobertura = :cobertura, comentario = :comentario WHERE id = :id');
         $stmt->bindParam(':ssid', $this->ssid, PDO::PARAM_STR);
         $stmt->bindParam(':rol', $this->rol, PDO::PARAM_STR);
         $stmt->bindParam(':id_equipo', $this->id_equipo, PDO::PARAM_INT);
         $stmt->bindParam(':num_site', $this->num_site, PDO::PARAM_INT);
         $stmt->bindParam(':panel_ant', $this->panel_ant, PDO::PARAM_INT);
         $stmt->bindParam(':activo', $this->activo, PDO::PARAM_INT);
         $stmt->bindParam(':cobertura', $this->cobertura, PDO::PARAM_STR);
         $stmt->bindParam(':comentario', $this->comentario, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA . ' (ssid, rol, id_equipo, num_site, panel_ant, activo, cobertura, comentario) VALUES(:ssid, :rol, :id_equipo, :num_site, :panel_ant, :activo, :cobertura, :comentario)');
      $stmt->bindParam(':ssid', $this->ssid, PDO::PARAM_STR);
      $stmt->bindParam(':rol', $this->rol, PDO::PARAM_STR);
      $stmt->bindParam(':id_equipo', $this->id_equipo, PDO::PARAM_INT);
      $stmt->bindParam(':num_site', $this->num_site, PDO::PARAM_INT);
      $stmt->bindParam(':panel_ant', $this->panel_ant, PDO::PARAM_INT);
      $stmt->bindParam(':activo', $this->activo, PDO::PARAM_INT);
      $stmt->bindParam(':cobertura', $this->cobertura, PDO::PARAM_STR);
      $stmt->bindParam(':comentario', $this->comentario, PDO::PARAM_STR);
      $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'Ssid -> ' . $this->getSsid() . '|',
                      'Rol -> ' . $this->getRol()  . '|',
                      'Id_equipo -> ' . $this->getId_equipo()  . '|',
                      'Num_site -> ' . $this->getNum_site()  . '|',
                      'Panel_ant -> ' . $this->getPanel_ant()  . '|',
                      'Activo -> ' . $this->getActivo()  . '|',
                      'Comentario -> ' . $this->getComentario()  . '|',
                      'Cobertura -> ' . $this->getCobertura()  . '|',
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        return $resultado;
      } else return false;
    }
    private function obternerDiferencias () {
      $datoDB = new Panel ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getSsid() != $datoDB->getSsid()) {
        $resultado ['changes'] .= 'Ssid->' . $datoDB->getSsid() . 'X' . $this->getSsid();
      }
      if ($this->getRol() != $datoDB->getRol()) {
        $resultado ['changes'] .= '|Rol->' . $datoDB->getRol() . 'X' . $this->getRol();
      }
      if ($this->getId_equipo() != $datoDB->getId_equipo()) {
        $resultado ['changes'] .= '|Id_equipo->' . $datoDB->getId_equipo() . 'X' . $this->getId_equipo();
      }
      if ($this->getNum_site() != $datoDB->getNum_site()) {
        $resultado ['changes'] .= '|Num_getNum_site->' . $datoDB->getNum_site() . 'X' . $this->getNum_site();
      }
      if ($this->getPanel_ant() != $datoDB->getPanel_ant()) {
        $resultado ['changes'] .= '|Panel_ant->' . $datoDB->getPanel_ant() . 'X' . $this->getPanel_ant();
      }
      if ($this->getActivo() != $datoDB->getActivo()) {
        $resultado ['changes'] .= '|Activo->' . $datoDB->getActivo() . 'X' . $this->getActivo();
      }
      if ($this->getComentario() != $datoDB->getComentario()) {
        $resultado ['changes'] .= '|Comentario->' . $datoDB->getComentario() . 'X' . $this->getComentario();
      }
      if ($this->getCobertura() != $datoDB->getCobertura()) {
        $resultado ['changes'] .= '|Cobertura->' . $datoDB->getCobertura() . 'X' . $this->getCobertura();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      $where = '';
      if ($query != '') {
        $where = "  WHERE upper (ssid) LIKE upper (:query)";
        $query = '%'.$query.'%';
        }
      $consulta = 'SELECT id FROM ' . self::TABLA . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
    
    public static function existeEquipoInsertado($datoId) { //false si no existe || idPanel SI es panel || true si es contrato 
      //verificar si el equipo ya esta insertado otro panel
      //verificar si el equipo ya esta insertado en un contrato.
        return false;
    }
    public static function getNombreArchivoCobertura ($datoId) {
      $panel = new Panel ($datoId);
      return $panel->getCobertura();
    }
    public static function getEquipoNoInsertados() {
      //listar los equipos para paneles en el rango 10.10.0.x
      $consulta = "select id, nombre, ip, fecha_baja from equipos where ip like :rango and id not in (select p.id_equipo from paneles as p)";
      $stmt = MyPdo::getStatement($consulta);
      $rango = self::RANGO_IP_PANELES;
      $stmt->bindParam(':rango', $rango, PDO::PARAM_STR);
      $stmt->execute();
      if ($stmt->rowCount() >= 1) {
        return $stmt->fetchAll();
      }
      return false;
    }
}// fin de la clase