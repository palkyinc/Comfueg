<?php
class Dispositivo
{
    private $id;
    private $marca;
    private $modelo;
    private $cod_comfueg;
    private $descripcion;
    private $errores = null;
    private const TABLA = 'dispositivos';

    public function __construct($dato = null)
    {
      if (is_array($dato)) {
          $this->setDatosPorArray($dato);
        } elseif (is_numeric($dato)) {
                $this->obtenerDispositivoPorId($dato);
              }
    }
    
    public function obtenerDispositivoPorId($datoId)
    {
	    $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE id = :datoId');
	      $stmt->bindParam (':datoId', $datoId, PDO::PARAM_INT );
	              if ($stmt->execute())
	                  {
	                    $bdato = $stmt->fetch();
	                    $this->setDatosPorArray($bdato);
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
      if (!$this->setMarca($datoArray['marca'])) {
        $this->errores .= 'Error en la Marca <br>';
      }
      if (!$this->setModelo($datoArray['modelo'])) {
        $this->errores .= 'Error en el Modelo <br>';
      }
      if (!$this->setCod_comfueg($datoArray['cod_comfueg'])) {
        $this->errores .= 'Error en el Código Comfueg <br>';
      }
      if (!$this->setDescripcion($datoArray['descripcion'])) {
        $this->errores .= 'Error en la Descripción <br>';
      }
      if ($this->errores !== null) {
        return false;
      }else {return true;}
    }
  public function getId () {
      return $this->id;
    }
  public function getMarca () {
      return $this->marca;
    }
  public function getModelo () {
      return $this->modelo;
    }
  public function getCod_comfueg () {
      return $this->cod_comfueg;
    }
  public function getDescripcion () {
      return $this->descripcion;
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
  public function setMarca ($dato) {
      $dato = trim($dato);
      if (strlen($dato) < 3 || strlen($dato) > 45 ) {
        return false;
      }else {
        $this->marca = $dato;
        return true;
        }
  }
  public function setModelo ($dato) {
    $dato = trim($dato);
    if (strlen($dato) < 3 || strlen($dato) > 45 ) {
      return false;
    }else {
      $this->modelo = $dato;
      return true;
      }
  }
  public function setCod_comfueg ($dato) {
    $dato = trim($dato);
      if ((!(MyPdo::checkCod_comfueg($dato, self::TABLA)) || (MyPdo::checkCod_comfueg($dato, self::TABLA)) === $this->id) && !(strlen($dato) < 3 || strlen($dato) > 45 )) {
        $this->cod_comfueg = $dato;
        return true;
      }
      return false;
  }
  public function setDescripcion ($dato) {
    $dato = trim($dato);
    if (strlen($dato) > 45 ) {
      return false;
    }else {
      $this->descripcion = $dato;
      return true;
      }
  }
  public static function getCollectionDispositivo ($query, $paginas) {
    $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (cod_comfueg) LIKE upper (:query) LIMIT :paginas, 20";
    $stmt = MyPdo::getStatement($consulta);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() >= 1) {
          return $stmt->fetchAll(PDO::FETCH_CLASS, 'Dispositivo');
      }
    return false;
  }
  public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA .' SET marca = :marca, modelo = :modelo, cod_comfueg = :cod_comfueg, descripcion = :descripcion WHERE id = :id');
         $stmt->bindParam(':marca', $this->marca, PDO::PARAM_STR);
         $stmt->bindParam(':modelo', $this->modelo, PDO::PARAM_STR);
         $stmt->bindParam(':cod_comfueg', $this->cod_comfueg, PDO::PARAM_STR);
         $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA .' (marca, modelo, cod_comfueg, descripcion) VALUES(:marca, :modelo, :cod_comfueg, :descripcion)');
         $stmt->bindParam(':marca', $this->marca, PDO::PARAM_STR);
         $stmt->bindParam(':modelo', $this->modelo, PDO::PARAM_STR);
         $stmt->bindParam(':cod_comfueg', $this->cod_comfueg, PDO::PARAM_STR);
         $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
         $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'Marca->' . $this->getMarca() . '|Modelo->' . $this->getModelo() . '|Cod_comfueg->' . $this->getCod_comfueg() . '|Descripcion->' . $this->getDescripcion(),
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        return $resultado;
      } else return false;
    }
    private function obternerDiferencias () {
      $datoDB = new Dispositivo ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getMarca() != $datoDB->getMarca()) {
        $resultado ['changes'] .= 'Marca->' . $datoDB->getMarca() . 'X' . $this->getMarca();
      }
      if ($this->getModelo() != $datoDB->getModelo()) {
        $resultado ['changes'] .= '|Modelo->' . $datoDB->getModelo() . 'X' . $this->getModelo();
      }
      if ($this->getCod_comfueg() != $datoDB->getCod_comfueg()) {
        $resultado ['changes'] .= '|Cod_comfueg->' . $datoDB->getCod_comfueg() . 'X' . $this->getCod_comfueg();
      }
      if ($this->getDescripcion() != $datoDB->getDescripcion()) {
        $resultado ['changes'] .= ' |Descripcion->' . $datoDB->getDescripcion() . 'X' . $this->getDescripcion();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      $where = '';
      if ($query != '') {
        $where = "  WHERE upper (cod_comfueg) LIKE upper (:query)";
        $query = '%'.$query.'%';
        }
      $consulta = 'SELECT id FROM ' . self::TABLA . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
}//final de la clase
