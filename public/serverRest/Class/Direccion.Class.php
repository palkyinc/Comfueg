<?php

class Direccion
{
    private $id;
    private $id_calle;
    private $numero;
    private $id_barrio;
    private $id_ciudad;
    private $errores = null;
    private const TABLA = 'direcciones';

    public function __construct($dato = null)
    {
      if (is_array($dato)) {
          $this->setDatosPorArray($dato);
        } elseif (is_numeric($dato)) {
                $this->obtenerDireccionPorId($dato);
              }
    }
    
    public function obtenerDireccionPorId($datoId)
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
      if (!$this->setIdCalle($datoArray['id_calle'])) {
        $this->errores .= 'Error en la Calle <br>';
      }
      if (!$this->setNumero($datoArray['numero'])) {
        $this->errores .= 'Error en el Número <br>';
      }
      if (!$this->setIdBarrio($datoArray['id_barrio'])) {
        $this->errores .= 'Error en el Barrio <br>';
      }
      if (!$this->setIdCiudad($datoArray['id_ciudad'])) {
        $this->errores .= 'Error en la Ciudad <br>';
      }
      if ($this->errores !== null) {
        return false;
      }else {return true;}
    }
    public function getId () {
      return $this->id;
    }
  	public function getIdCalle () {
  	      return $this->id_calle;
  	    }
  	public function getNombreCalle() {
  		$calle = new Calle ($this->getIdCalle());
  		return $calle->getNombre();
  	}
  	public function getNumero () {
        return $this->numero;
      }
  	public function getIdBarrio () {
        return $this->id_barrio;
      }
  	public function getNombreBarrio() {
  		$barrio = new Barrio ($this->getIdBarrio());
  		return $barrio->getNombre();
  	}
  	public function getIdCiudad () {
        return $this->id_ciudad;
      }
  	public function getNombreCiudad() {
  		$ciudad = new Ciudad ($this->getIdCiudad());
  		return $ciudad->getNombre();
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
    public function setIdCalle ($dato) {
      if (filter_var($dato, FILTER_VALIDATE_INT)) {
        $calle = new Calle();
        if ($calle->obtenerCallePorId($dato)) {
	        $this->id_calle = $dato;
	        return true;
        }
       return false;
      }
    }
    public function setNumero ($dato) {
      $dato = trim($dato);
      if (filter_var($dato, FILTER_VALIDATE_INT)) {
        $this->numero = $dato;
        return true;
      }else {
            return false;
            }
    }
    public function setIdBarrio ($dato) {
      if (filter_var($dato, FILTER_VALIDATE_INT)) {
        $barrio = new Barrio();
        if ($barrio->obtenerBarrioPorId($dato)) {
	        $this->id_barrio = $dato;
	        return true;
        }
       return false;
      }
    }
    public function setIdCiudad ($dato) {
      if (filter_var($dato, FILTER_VALIDATE_INT)) {
        $ciudad = new Ciudad();
        if ($ciudad->obtenerCiudadPorId($dato)) {
	        $this->id_ciudad = $dato;
	        return true;
        }
       return false;
      }
    }
    public static function getCollectionDireccion ($query, $paginas) {
      $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (id) LIKE upper (:query) LIMIT :paginas, 20";
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Direccion');
        }
      return false;
    }
    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA .' SET id_calle = :id_calle, numero = :numero, id_barrio = :id_barrio, id_ciudad = :id_ciudad WHERE id = :id');
         $stmt->bindParam(':id_calle', $this->id_calle, PDO::PARAM_INT);
         $stmt->bindParam(':numero', $this->numero, PDO::PARAM_INT);
         $stmt->bindParam(':id_barrio', $this->id_barrio, PDO::PARAM_INT);
         $stmt->bindParam(':id_ciudad', $this->id_ciudad, PDO::PARAM_INT);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
        //MisFunciones::imprimirConPrintR($this); die;
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA .' (id_calle, numero, id_barrio, id_ciudad) VALUES(:id_calle, :numero, :id_barrio, :id_ciudad)');
         $stmt->bindParam(':id_calle', $this->id_calle, PDO::PARAM_INT);
         $stmt->bindParam(':numero', $this->numero, PDO::PARAM_INT);
         $stmt->bindParam(':id_barrio', $this->id_barrio, PDO::PARAM_INT);
         $stmt->bindParam(':id_ciudad', $this->id_ciudad, PDO::PARAM_INT);
         $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'IdCalle -> ' . $this->getNombreCalle() . '|',
                      'Numero -> ' . $this->getNumero()  . '|',
                      'Barrio -> ' . $this->getNombreBarrio()  . '|',
                      'Ciudad -> ' . $this->getNombreCiudad(),
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        return $resultado;
      } else return false;
    }
    private function obternerDiferencias () {
      $datoDB = new Direccion ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getIdCalle() != $datoDB->getIdCalle()) {
        $resultado ['changes'] .= 'Calle->' . $datoDB->getIdCalle(). 'X' . $this->getNombreCalle();
      }
      if ($this->getNumero() != $datoDB->getNumero()) {
        $resultado ['changes'] .= '|Numero->' . $datoDB->getNumero() . 'X' . $this->getNumero();
      }
      if ($this->getIdBarrio() != $datoDB->getIdBarrio()) {
        $resultado ['changes'] .= '|Barrio->' . $datoDB->getIdBarrio() . 'X' . $this->getNombreBarrio();
      }
      if ($this->getIdCiudad() != $datoDB->getIdCiudad()) {
        $resultado ['changes'] .= '|Ciudad->' . $datoDB->getIdCiudad() . 'X' . $this->getNombreCiudad();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      $where = '';
      if ($query != '') {
        $where = "  WHERE upper (id) LIKE upper (:query)";
        $query = '%'.$query.'%';
        }
      $consulta = 'SELECT id FROM ' . self::TABLA . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
}//fin de la clase
