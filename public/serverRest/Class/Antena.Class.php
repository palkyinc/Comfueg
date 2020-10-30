<?php

class Antena
{
private $id = '';
private $descripcion = '';
private $cod_comfueg = '';
private $errores = null;
private  const TABLA = 'antenas';

public function __construct($dato = null)
{
    if (is_array($dato)) {
      $this->setDatosPorArray($dato);
    } elseif (is_numeric($dato)) {
            $this->obtenerAntenaPorId($dato);
           }
}

    public function obtenerAntenaPorId($datoId)
      {
        $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE id = ' . $datoId);
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

    public static function getAntenaById ($datoId) {
      $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE id = :datoId' );
      $stmt->bindParam (':datoId', $datoId, PDO::PARAM_INT );
      $stmt->execute();
      if ($stmt->rowCount() === 1) {
        return $stmt->fetchObject('Antena');
      }
      return false;
    }

    public static function getCollectionAntena ($query, $paginas) {
      $stmt = MyPdo::getStatement('SELECT * FROM ' . self::TABLA . ' WHERE upper (cod_comfueg) LIKE upper (:query) LIMIT :paginas, 20' );
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      //MisFunciones::imprimirConPrintR($stmt->errorinfo());die;
      if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Antena');
        }
      return false;
    }

    public function guardarEnDb(){
      if($this->id) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA .' SET cod_comfueg = :cod_comfueg, descripcion = :descripcion WHERE id = :id');
         $stmt->bindParam(':cod_comfueg', $this->cod_comfueg, PDO::PARAM_STR);
         $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
      }else /*Inserta*/ {
         $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA .' (cod_comfueg, descripcion) VALUES (:cod_comfueg, :descripcion)');
         $stmt->bindParam(':cod_comfueg', $this->cod_comfueg, PDO::PARAM_STR);
         $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
         $db = MyPdo::getConnection();
         $stmt->execute();
         $resultado = [
                      'id_auditable' => $db->lastInsertId(),
                      'action' => 'NUEVO',
                      'changes' => 'Descripcion ->' . $this->getDescripcion() . ' | Cod_comfueg ->' . $this->getCod_comfueg(),
                      ];
         //$this->setId($stmt->lastInsertId());
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = 'antenas';
        return $resultado;
      } else return false;
    }

    private function obternerDiferencias () {
      $datoDB = new Antena ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getDescripcion() != $datoDB->getDescripcion()) {
        $resultado ['changes'] .= 'Descripcion->' . $datoDB->getDescripcion() . 'X' . $this->getDescripcion();
      }
      if ($this->getCod_comfueg() != $datoDB->getCod_comfueg()) {
        $resultado ['changes'] .= '|Cod Comfueg->' . $datoDB->getCod_comfueg() . 'X' . $this->getCod_comfueg();
      }
      return $resultado;
    }

    public static function getCantElementos ($query = "") {
      $where = ($query != '') ? "  WHERE upper (cod_comfueg) LIKE upper (_utf8'%$query%') COLLATE utf8_general_ci" : '';
      $consulta = 'SELECT id FROM ' . self::TABLA . ' ' . $where;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->execute();
      return $stmt->rowCount();
    }
    
    public function setDatosPorArray ($datoArray) {
      if (!$this->setId($datoArray['id'])) {
        $this->errores .= 'Error en el ID <br>';
      }
      if (!$this->setDescripcion($datoArray['descripcion'])) {
        $this->errores .= 'Error en la Descripción <br>';
      }
      if (!$this->setCod_comfueg($datoArray['cod_comfueg'])) {
        $this->errores .= 'Error en el Código Comfueg <br>';
      }
      if ($this->errores !== null) {
        return false;
      }else {return true;}
    }

    public function getId () {
      return $this->id;
    }
    public function getDescripcion () {
      return $this->descripcion;
    }
    public function getCod_comfueg () {
      return $this->cod_comfueg;
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
    public function setDescripcion ($dato) {
      $dato = trim($dato);
      if (strlen($dato) < 3 || strlen($dato) > 30 ) {
        return false;
      }else {
        $this->descripcion = $dato;
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
}//fin de la clase