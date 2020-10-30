<?php
class Plan
{
    private $id;
    private $nombre;
    private $bajada;
    private $subida;
    private $descripcion;
    private const TABLA = 'planes';

    public function __construct($dato = null)
    {
      if (is_array($dato)) {
          $this->setDatosPorArray($dato);
        } elseif (is_numeric($dato)) {
                $this->obtenerPlanPorId($dato);
              }
    }
    // implementación de métodos
    public function obtenerPlanPorId($datoId)
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
      if (!$this->setNombre($datoArray['nombre'])) {
        $this->errores .= 'Error en el Nombre <br>';
      }
      if (!$this->setBajada($datoArray['bajada'])) {
        $this->errores .= 'Error en la Bajada <br>';
      }
      if (!$this->setSubida($datoArray['subida'])) {
        $this->errores .= 'Error en la Subida <br>';
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
    public function getNombre () {
        return $this->nombre;
        }
    public function getBajada () {
        return $this->bajada;
        }
    public function getSubida () {
        return $this->subida;
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
    public function setNombre ($dato) {
        $dato = trim($dato);
        if (strlen($dato) < 3 || strlen($dato) > 45 ) {
            return false;
        }else {
            $this->nombre = $dato;
            return true;
            }
    }
    public function setBajada ($dato) {
        $dato = trim($dato);
    if ($dato === '' || filter_var($dato, FILTER_VALIDATE_INT)) {
      $this->bajada = $dato;
      return true;
    }else {
          return false;
          }
    }
    public function setSubida ($dato) {
        $dato = trim($dato);
        if ($dato === '' || filter_var($dato, FILTER_VALIDATE_INT)) {
        $this->subida = $dato;
        return true;
        }else {
            return false;
            }
    }
    public function setDescripcion ($dato) {
        $dato = trim($dato);
        if (strlen($dato) < 3 || strlen($dato) > 100 ) {
        return false;
        }else {
        $this->descripcion = $dato;
        return true;
        }
    }
    public static function getCollectionPlan ($query, $paginas) {
    $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (nombre) LIKE upper (:query) LIMIT :paginas, 20";
    $stmt = MyPdo::getStatement($consulta);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() >= 1) {
          return $stmt->fetchAll(PDO::FETCH_CLASS, 'Plan');
      }
    return false;
    }
    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA .' SET nombre = :nombre, bajada = :bajada, subida = :subida, descripcion = :descripcion WHERE id = :id');
         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
         $stmt->bindParam(':bajada', $this->bajada, PDO::PARAM_INT);
         $stmt->bindParam(':subida', $this->subida, PDO::PARAM_INT);
         $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA .' (nombre, bajada, subida, descripcion) VALUES(:nombre, :bajada, :subida, :descripcion)');
         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
         $stmt->bindParam(':bajada', $this->bajada, PDO::PARAM_INT);
         $stmt->bindParam(':subida', $this->subida, PDO::PARAM_INT);
         $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
         $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'Nombre->' . $this->getNombre() . '|Bajada->' . $this->getBajada() . '|Subida->' . $this->getSubida() . '|Descripcion->' . $this->getDescripcion(),
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        return $resultado;
      } else return false;
    }
    
    private function obternerDiferencias () {
      $datoDB = new Plan ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getNombre() != $datoDB->getNombre()) {
        $resultado ['changes'] .= 'Nombre->' . $datoDB->getNombre() . 'X' . $this->getNombre();
      }
      if ($this->getBajada() != $datoDB->getBajada()) {
        $resultado ['changes'] .= '|Bajada->' . $datoDB->getBajada() . 'X' . $this->getBajada();
      }
      if ($this->getSubida() != $datoDB->getSubida()) {
        $resultado ['changes'] .= '|Subida->' . $datoDB->getSubida() . 'X' . $this->getSubida();
      }
      if ($this->getDescripcion() != $datoDB->getDescripcion()) {
        $resultado ['changes'] .= ' |Descripcion->' . $datoDB->getDescripcion() . 'X' . $this->getDescripcion();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      $where = '';
      if ($query != '') {
        $where = "  WHERE upper (nombre) LIKE upper (:query)";
        $query = '%'.$query.'%';
        }
      $consulta = 'SELECT id FROM ' . self::TABLA . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
}//fin de la clase