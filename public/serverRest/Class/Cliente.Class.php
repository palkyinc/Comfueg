<?php

class Cliente
{
  private $id;
  private $apellido;
  private $nombre;
  private $cod_area_tel;
  private $telefono;
  private $cod_area_cel;
  private $celular;
  private $email;
  private $hash_email;
  private $email_verificated;
  private $errores;
  private $nuevo = null;
  private  const TABLA = 'clientes';

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
      if (isset($datoArray['nuevo'])) {
        $this->nuevo = $datoArray['nuevo'];
      }
      if (!$this->setId($datoArray['id'])) {
        $this->errores .= 'Error en el ID <br>';
      }
      if (!$this->setApellido($datoArray['apellido'])) {
        $this->errores .= 'Error en el Apellido <br>';
      }
      if (!$this->setNombre($datoArray['nombre'])) {
        $this->errores .= 'Error en el Nombre <br>';
      }
      if (!$this->setCod_area_tel($datoArray['cod_area_tel'])) {
        $this->errores .= 'Error en el Código de área del teléfono <br>';
      }
      if (!$this->setTelefono($datoArray['telefono'])) {
        $this->errores .= 'Error en el Teléfono <br>';
      }
      if (!$this->setCod_area_cel($datoArray['cod_area_cel'])) {
        $this->errores .= 'Error en el Código de área del celular <br>';
      }
      if (!$this->setCelular($datoArray['celular'])) {
        $this->errores .= 'Error en el celular <br>';
      }
      if (!$this->setEmail($datoArray['email'])) {
        $this->errores .= 'Error en el Email <br>';
      }
      if (!$this->setHash_email($datoArray['hash_email'])) {
        $this->errores .= 'Error en el Hash email <br>';
      }
      if (!$this->setEmail_verificated($datoArray['email_verificated'])) {
        $this->errores .= 'Error en el email verificado<br>';
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
    public function getApellido () {
      return $this->apellido;
    }
    public function getNomyApe () {
        if ($this->nombre !== '') {
            return $this->apellido . ', ' . $this->nombre;
        }
        return $this->getApellido();
    }
    public function getCod_area_tel() {
      return $this->cod_area_tel;
    }
    private function getCod_area_numero ($datoId) {
        $codArea = new CodigodeArea ($datoId);
        return $codArea->getCodigoDeArea();
    }
    public function getTelefono () {
      return $this->telefono;
    }
    public function getNumTelefono() {
      if ($this->telefono) {
      $codArea = $this->getCod_area_numero($this->cod_area_tel);
      return $codArea . $this->telefono;
      }
    }
    public function getNumCelular() {
      if ($this->celular) {
      $codArea = $this->getCod_area_numero($this->cod_area_cel);
      return $codArea . '-15-' . $this->celular;
      }
    }
    public function getCod_area_cel () {
      return $this->cod_area_cel;
    }
    public function getCelular () {
      return $this->celular;
    }
    public function getEmail () {
      return $this->email;
    }
    public function getHash_email () {
      return $this->hash_email;
    }
    public function getEmail_verificated () {
      return $this->email_verificated;
    }
    public function getErrores () {
      return $this->errores;
    }
    public function setErrores () {
      $this->errores = null;
    }
    public function setEmail_verificated ($dato) {
      $this->email_verificated = $dato;
      return true;
    }
    public function setId ($dato) {
      if ($dato !== '' && filter_var($dato, FILTER_VALIDATE_INT)) {
        if ((!self::getCatElementosAdvance($dato, 'id') && $this->nuevo) || (self::getCatElementosAdvance($dato, 'id') && !$this->nuevo)) {
          $this->id = $dato;
          return true;
        }
      }
      return false;
    }
    public function setNombre ($dato) {
      $dato = trim($dato);
      $dato = strtolower($dato);
      $dato = ucfirst($dato);
      if (strlen($dato) < 3 || strlen($dato) > 45 ) {
        return false;
      }else {
        $this->nombre = $dato;
        return true;
        }
    }
    public function setApellido($dato)
    {
        $dato = trim($dato);
        $dato = strtoupper ($dato);
        if (strlen($dato) < 3 || strlen($dato) > 45) {
            return false;
        } else {
            $this->apellido = $dato;
            return true;
        }
    }
    private function validarCodArea ($dato) {
        $codArea = new CodigoDeArea;
        if ($codArea->obtenerCodigoDeAreaPorId($dato) && filter_var($dato, FILTER_VALIDATE_INT)) {
            return true;
        }
        return false;
    }
    public function setCod_area_tel ($dato) {
        $dato = trim($dato);
        if ($this->validarCodArea($dato)) {
            $this->cod_area_tel = $dato;
            return true;
        }else {
                return false;
                }
    }
    private function validarNumeroTelefono ($numero, $cod_area) {
        if (filter_var($numero, FILTER_VALIDATE_INT)) {
            $numero_completo = $cod_area . $numero;
            if (strlen($numero_completo) == 10) {
                return true;
            }
        }
        return false;
    } 
    public function setTelefono ($dato) {
        $dato = trim($dato);
      if ($this->validarNumeroTelefono($dato, $this->getCod_area_numero($this->cod_area_tel)) || $dato == '') {
        $this->telefono = $dato;
        return true;
      }else {
            return false;
            }
    }
    public function setCod_area_cel($dato)
    {
        if ($this->validarCodArea($dato)) {
            $this->cod_area_cel = $dato;
            return true;
        } else {
            return false;
        }
    }
    public function setCelular($dato)
    {
        $dato = trim($dato);
        if ($this->validarNumeroTelefono($dato, $this->getCod_area_numero($this->cod_area_cel))) {
            $this->celular = $dato;
            return true;
        } else {
            return false;
        }
    }
    public function setEmail($dato)
    {
        $dato = trim($dato);
        $dato = strtolower($dato);
        if (filter_var($dato, FILTER_VALIDATE_EMAIL)) {
            $this->email = $dato;
            return true;
        }
        return false;
    }
    public function setHash_email($dato = null)
    {
      if ($dato != null) {
        $this->hash_email = $dato;
      } else echo 'Hay que generar el HASH';
      return true;
    }
      
    
    public static function getCollectionCliente ($query, $paginas) {
      $consulta = "SELECT * FROM ".self::TABLA." WHERE upper (apellido) LIKE upper (:query) LIMIT :paginas, 20";
      $stmt = MyPdo::getStatement($consulta);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':paginas', $paginas, PDO::PARAM_INT);
      $stmt->execute();
      //MisFunciones::imprimirConPrintR($stmt->errorInfo()); die;
      if ($stmt->rowCount() >= 1) {
         return $stmt->fetchAll(PDO::FETCH_CLASS, 'Cliente');
        }
      return false;
    }
    public function guardarEnDb(){
      //MisFunciones::imprimirConPrintR($this); die;
      if(!$this->nuevo) /*Modifica*/ {
        $resultado = $this->obternerDiferencias();
         $stmt = MyPdo::getStatement('UPDATE ' . self::TABLA . ' SET nombre = :nombre, apellido = :apellido, cod_area_tel = :cod_area_tel, telefono = :telefono, cod_area_cel = :cod_area_cel, celular = :celular, email = :email, hash_email = :hash_email, email_verificated = :email_verificated WHERE id = :id');
         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
         $stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
         $stmt->bindParam(':cod_area_tel', $this->cod_area_tel, PDO::PARAM_INT);
         $stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
         $stmt->bindParam(':cod_area_cel', $this->cod_area_cel, PDO::PARAM_INT);
         $stmt->bindParam(':celular', $this->celular, PDO::PARAM_STR);
         $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
         $stmt->bindParam(':hash_email', $this->hash_email, PDO::PARAM_STR);
         $stmt->bindParam(':email_verificated', $this->email_verificated, PDO::PARAM_INT);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         $stmt->execute();
        }else /*Inserta*/ {
          $stmt = MyPdo::getStatement('INSERT INTO ' . self::TABLA . ' (id, nombre, apellido, cod_area_tel, telefono, cod_area_cel, celular, email, hash_email, email_verificated) VALUES(:id, :nombre, :apellido, :cod_area_tel, :telefono, :cod_area_cel, :celular, :email, :hash_email, :email_verificated)');
          $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
          $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
          $stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
          $stmt->bindParam(':cod_area_tel', $this->cod_area_tel, PDO::PARAM_INT);
          $stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
          $stmt->bindParam(':cod_area_cel', $this->cod_area_cel, PDO::PARAM_INT);
          $stmt->bindParam(':celular', $this->celular, PDO::PARAM_STR);
          $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
          $stmt->bindParam(':hash_email', $this->hash_email, PDO::PARAM_STR);
          $stmt->bindParam(':email_verificated', $this->email_verificated, PDO::PARAM_INT);
          $db = MyPdo::getConnection();
          $stmt->execute();
          $resultado = [
                  'id_auditable' => $db->lastInsertId(),
                  'action' => 'NUEVO',
                  'changes' => 'Nombre -> ' . $this->getNombre() . '|',
                  'Apellido -> ' . $this->getApellido()  . '|',
                  'Cod_area_tel -> ' . $this->getCod_area_tel()  . '|',
                  'Telefono -> ' . $this->getTelefono()  . '|',
                  'Cod_area_cel -> ' . $this->getCod_area_cel()  . '|',
                  'Celular -> ' . $this->getCelular()  . '|',
                  'Email -> ' . $this->getEmail()  . '|',
                  'Hash_email -> ' . $this->getHash_email()  . '|',
                  'Email_verificated -> ' . $this->getEmail_verificated(),
                  ];
      }
      if($stmt->rowCount() === 1) {
        $resultado ['item_auditable'] = self::TABLA;
        $this->nuevo = 0;
        return $resultado;
      } else return false;
    }
    
    private function obternerDiferencias () {
      $datoDB = new Cliente ($this->getId());
      $resultado ['id_auditable'] = $this->getId();
      $resultado ['action'] = 'MODIFICA';
      $resultado ['changes'] = '';
      if ($this->getNombre() != $datoDB->getNombre()) {
        $resultado ['changes'] .= 'Nombre->' . $datoDB->getNombre() . 'X' . $this->getNombre();
      }
      if ($this->getApellido() != $datoDB->getApellido()) {
        $resultado ['changes'] .= '|Apellido->' . $datoDB->getApellido() . 'X' . $this->getApellido();
      }
      if ($this->getCod_area_tel() != $datoDB->getCod_area_tel()) {
        $resultado ['changes'] .= '|Cod_area_tel->' . $datoDB->getCod_area_tel() . 'X' . $this->getCod_area_tel();
      }
      if ($this->getTelefono() != $datoDB->getTelefono()) {
        $resultado ['changes'] .= '|Telefono->' . $datoDB->getTelefono() . 'X' . $this->getTelefono();
      }
      if ($this->getCod_area_cel() != $datoDB->getCod_area_cel()) {
        $resultado ['changes'] .= '|Cod_area_cel->' . $datoDB->getCod_area_cel() . 'X' . $this->getCod_area_cel();
      }
      if ($this->getCelular() != $datoDB->getCelular()) {
        $resultado ['changes'] .= '|Celular->' . $datoDB->getCelular() . 'X' . $this->getCelular();
      }
      if ($this->getEmail() != $datoDB->getEmail()) {
        $resultado ['changes'] .= '|Email->' . $datoDB->getEmail() . 'X' . $this->getEmail();
        $this->email_verificated = 0;
      }
      if ($this->getHash_email() != $datoDB->getHash_email()) {
        $resultado ['changes'] .= '|Hash_email->' . $datoDB->getHash_email() . 'X' . $this->getHash_email();
      }
      if ($this->getEmail_verificated() != $datoDB->getEmail_verificated()) {
        $resultado ['changes'] .= '|Email_verificated->' . $datoDB->getEmail_verificated() . 'X' . $this->getEmail_verificated();
      }
      return $resultado;
    }
    public static function getCantElementos ($query = "") {
      return self::getCatElementosAdvance($query, 'apellido');
    }

    private static function getCatElementosAdvance ($query, $columna) {
    $where = '';
    if ($query != '') {
      $where = "  WHERE upper ($columna) LIKE upper (:query)";
      $query = '%' . $query . '%';
    }
    $consulta = 'SELECT id FROM ' . self::TABLA . $where;
    $stmt = MyPdo::getStatement($consulta);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount();
    }
    public function confirmarEmail() {
      //Crear y grabar hash
      //crear link
      //enviar email

      // PD archivo respuesta de verificacion.
    }
}// fin de la clase