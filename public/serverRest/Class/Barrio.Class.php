<?php

class Barrio
{
    private $id;
    private $nombre;
    private $errores = null;
    private  const TABLA = 'barrios';

    public function __construct($dato = null)
    {
      if (is_array($dato)) {
          $this->setDatosPorArray($dato);
        } elseif (is_numeric($dato)) {
                $this->obtenerBarrioPorId($dato);
            	}
    }

    public function obtenerBarrioPorId ($datoId) {
      $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE id = :datoId');
      $stmt->bindParam (':datoId', $datoId, PDO::PARAM_INT );
      $stmt->execute();
              if ($stmt->rowCount() === 1)
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

    public static function getBarrioById ($datoId) {
      $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE id = :datoId' );
      $stmt->bindParam (':datoId', $datoId, PDO::PARAM_INT );
      $stmt->execute();
      if ($stmt->rowCount() === 1) {
        return $stmt->fetchObject('Antena');
      }
      return false;
    }

    public static function getCollectionBarrio ($query, $paginas) {
      $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (nombre) LIKE upper (:query) LIMIT :paginas, 20";
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Barrio');
        }
      return false;
    }

    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA .' SET nombre = :nombre WHERE id = :id');
         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA .' (nombre) VALUES(:nombre)');
         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
         $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'Nombre ->' . $this->getNombre(),
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = 'barrios';
        return $resultado;
      } else return false;
    }

     private function obternerDiferencias () {
      $datoDB = new Barrio ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getNombre() != $datoDB->getNombre()) {
        $resultado ['changes'] .= 'Nombre->' . $datoDB->getNombre() . 'X'  . $this->getNombre();
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
