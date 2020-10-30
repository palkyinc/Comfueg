<?php

class CodigoDeArea
{
  private $id;
  private $codigoDeArea;
  private $provincia;
  private $localidades;
  private $errores = null;
  private  const TABLA = 'codigosDeArea';

  public function __construct($dato = null)
  {
    if (is_array($dato)) {
          $this->setDatosPorArray($dato);
        } elseif (is_numeric($dato)) {
                $this->obtenerCodigoDeAreaPorId($dato);
              }
  }

  public function obtenerCodigoDeAreaPorId($datoId)
  {
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
      if (!$this->setCodigoDeArea($datoArray['codigoDeArea'])) {
        $this->errores .= 'Error en el Código de Área <br>';
      }
      if (!$this->setProvincia($datoArray['provincia'])) {
        $this->errores .= 'Error en el Provincia <br>';
      }
      if (!$this->setLocalidades($datoArray['localidades'])) {
        $this->errores .= 'Error en las Localidades <br>';
      }
      if ($this->errores !== null) {
        return false;
      }else {return true;}
    }
    public function getId () {
      return $this->id;
    }
    public function getCodigoDeArea () {
      return $this->codigoDeArea;
    }
    public function getProvincia () {
      return $this->provincia;
    }
    public function getLocalidades () {
      return $this->localidades;
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
    public function setCodigoDeArea ($dato) {
      $dato = trim($dato);
      if (strlen($dato) === 6 ) {
        return false;
      }else {
        $this->codigoDeArea = $dato;
        return true;
        }
    }
    public function setProvincia ($dato) {
      if (strlen($dato) < 3 || strlen($dato) > 45 ) {
        return false;
      }else {
        $this->provincia = $dato;
        return true;
        }
    }
    public function setLocalidades ($dato) {
      if (strlen($dato) < 3 || strlen($dato) > 65535 ) {
        return false;
      }else {
        $this->localidades = $dato;
        return true;
        }
    }
    public static function getCollectionCodigoDeArea ($query, $paginas) {
      $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (codigoDeArea) LIKE upper (:query) LIMIT :paginas, 20";
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'CodigoDeArea');
        }
      return false;
    }
    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA .' SET codigoDeArea = :codigoDeArea, provincia = :provincia, localidades = :localidades WHERE id = :id');
         $stmt->bindParam(':codigoDeArea', $this->codigoDeArea, PDO::PARAM_STR);
         $stmt->bindParam(':provincia', $this->provincia, PDO::PARAM_STR);
         $stmt->bindParam(':localidades', $this->localidades, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA .' (codigoDeArea, provincia, localidades) VALUES(:codigoDeArea, :provincia, :localidades)');
         $stmt->bindParam(':codigoDeArea', $this->codigoDeArea, PDO::PARAM_STR);
         $stmt->bindParam(':provincia', $this->provincia, PDO::PARAM_STR);
         $stmt->bindParam(':localidades', $this->localidades, PDO::PARAM_STR);
         $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'CodigoDeArea -> ' . $this->getCodigoDeArea() . '|',
                      'Provincia -> ' . $this->getProvincia()  . '|',
                      'Localidades -> ' . $this->getLocalidades()  . '|',
                      ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        return $resultado;
      } else return false;
    }
    private function obternerDiferencias () {
      $datoDB = new CodigoDeArea ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getCodigoDeArea() != $datoDB->getCodigoDeArea()) {
        $resultado ['changes'] .= 'CodigoDeArea->' . $datoDB->getCodigoDeArea() . 'X' . $this->getCodigoDeArea();
      }
      if ($this->getProvincia() != $datoDB->getProvincia()) {
        $resultado ['changes'] .= '|Provincia->' . $datoDB->getProvincia() . 'X' . $this->getProvincia();
      }
      if ($this->getLocalidades() != $datoDB->getLocalidades()) {
        $resultado ['changes'] .= '|Localidades->' . $datoDB->getLocalidades() . 'X' . $this->getLocalidades();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      $where = '';
      if ($query != '') {
        $where = "  WHERE upper (codigoDeArea) LIKE upper (:query)";
        $query = '%'.$query.'%';
        }
      $consulta = 'SELECT id FROM ' . self::TABLA . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->rowCount();
    }
}// fin de la clase