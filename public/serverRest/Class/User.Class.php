<?php
class User
{
    private $id;
    private $usuario;
    private $nom_ape;
    private $nivel;
    private $vence_pass;
    private $email;
    private $activo;
    private $cumpleanio;
    
    public function __construct ($a = 0)
    {
      $this->id = null;
      $this->usuario = null;
      $this->nom_ape = 'Fulano de Tal';
      $this->nivel = new Nivel ();
      $this->vence_pass = null;
      $this->email = null;
      $this->activo = null;
      $this->cumpleanio = null;
      if ($a != 0 ) {
          $this->obtenerUnUsuarioPorId ($a);
      }
    }
    public function getId () {
      return $this->id;
    }
    public function getUsuario () {
      return $this->usuario;
    }
    public function getNom_ape () {
      return $this->nom_ape;
    }
    public function getNivel () {
      return $this->nivel;
    }
    public function getVence_pass () {
      return $this->vence_pass;
    }
    public function getEmail () {
      return $this->email;
    }
    public function getActivo () {
      return $this->activo;
    }
    public function getCumpleanio () {
      return $this->cumpleanio;
    }
    public function getNivelId () {
      return $this->nivel->getId();
    }
    public function getNivelNombre () {
      return $this->nivel->getNombre();
    }
    public function getVenceEN () {
      $ahora = date("Y-n-j H:i:s");
      return round ((strtotime($this->vence_pass)-strtotime($ahora))/60/60/24);
    }
    
    public function setId ($dato) {
      $this->id = $dato;
    }
    public function setUsuario ($dato) {
      $this->usuario = $dato;
    }
    public function setNom_ape ($dato) {
      $this->nom_ape = $dato;
    }
    public function setNivel ($dato) {
      $this->nivel = $dato;
    }
    public function setVence_pass ($dato) {
      $this->vence_pass = $dato;
    }
    public function setEmail ($dato) {
      $this->email = $dato;
    }
    public function setActivo ($dato) {
      $this->activo = $dato;
    }
    public function setCumpleanio ($dato) {
      $this->cumpleanio = $dato;
    }
    
    public function obtenerUnUsuarioPorId ($a) //obtener un usuario a partir de su ID
    {
      $consulta = "SELECT * FROM usuarios WHERE ID=$a";
      if ($busuario = MisFunciones::buscarEnBase($consulta))
            {
              $this->cargarDatosEnObjeto ($busuario);
              return true;
            }else {
              return false;
            }
    }
    private function cargarDatosEnObjeto ($busuario) {
              $this->setId($busuario['ID']);
              $this->setUsuario($busuario['USUARIO']);
              $this->setNom_ape($busuario['NOM_APE']);
              $this->setNivel(new Nivel($busuario['AUT_NIVEL']));
              $this->setVence_pass($busuario['VENCE_PASS']);
              $this->setEmail($busuario['EMAIL']);
              $this->setActivo($busuario['ACTIVO']);
              $this->setCumpleanio($busuario['CUMPLEANIO']);
    }
}