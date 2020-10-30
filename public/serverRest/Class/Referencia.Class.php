<?php

spl_autoload_register(function ($nombre_clase) {
  require_once $nombre_clase . '.Class.php';
});

class Referencia
{
    private $autenticado = 0;
    private $pagina = 'index';
    public $usuario;
    private $login;
    private $perfil;
    private $principal;
    private $contratos;
    private $internet;
    private $datos;
    private $ultAcceso;
    private $titulo = 'Falta Título';

    public function getAutenticado () {
      return $this->autenticado;
    }
    public function setAutenticado ($dato) {
      if ($dato == 1 || $dato == 0) {
      $this->autenticado = $dato;
      }
    }
    public function getTitulo () {
      return $this->titulo;
    }
    public function setTitulo ($dato) {
      $this->titulo = $dato;
    }
    public function getPagina () {
      return $this->pagina;
    }
    public function setPagina ($dato) {
      $this->pagina = $dato;
    }
    public function getUsuario () {
      return $this->usuario;
    }
    public function setUsuarioPorId ($dato) {
      $this->usuario = new User($dato);
    }
    public function getLogin () {
      return $this->login;
    }
    public function setLogin ($dato) { //0= ocultar, 1= mostrar
      if ($dato === 1) {
        $this->login = "";
      } elseif ($dato === 0) {
            $this->login = "ocultar";
            }
    }
    public function getPerfil () {
      return $this->perfil;
    }
    public function setPerfil ($dato) {//0= ocultar, 1= mostrar
      if ($dato === 1) {
        $this->perfil = "";
      } elseif ($dato === 0) {
            $this->perfil = "ocultar";
            }
    }
    public function getPrincipal () {
      return $this->principal;
    }
    public function setPrincipal ($dato) {
      if ($dato === 1) {
        $this->principal = "active";
      } elseif ($dato === 0) {
            $this->principal = "";
            }
    }
    public function getContratos () {
      return $this->contratos;
    }
    public function setContratos ($dato) {
      if ($dato === 1) {
        $this->contratos = "active";
      } elseif ($dato === 0) {
            $this->contratos = "";
            }
    }
    public function getInternet () {
      return $this->internet;
    }
    public function setInternet ($dato) {
      
      if ($dato === 1) {
        $this->internet = "active";
      } elseif ($dato === 0) {
            $this->internet = "";
            }
    }
    public function getDatos () {
      return $this->datos;
    }
    public function setDatos ($dato) {
      if ($dato === 1) {
        $this->datos = "active";
      } elseif ($dato === 0) {
            $this->datos = "";
            }
    }
    public function getUltAcceso () {
      return $this->ultAcceso;
    }
    public function setUltAcceso () {
      $ahora = date("Y-n-j H:i:s");
      $this->ultAcceso = $ahora;
    }

    public function noAutenticado () {
        $this->setAutenticado(0);
        $this->setPerfil(0);
        $this->setLogin(1);
    }
    public function autenticado () {
        $this->setAutenticado(1);
        $this->setPerfil(1);
        $this->setLogin(0);  
    }

    public function sesionExpiro() {
      $ahora = date("Y-n-j H:i:s");
      $tiempo_transcurrido = (strtotime($ahora)-strtotime($this->getUltAcceso()));
      echo $this->ultAcceso;
       if($tiempo_transcurrido >= 600)
           {
          //si pasaron 10 minutos o más
            $this->noAutenticado();
            return true;
           }
          else
          {
            $this->setUltAcceso();
            $this->autenticado();
            return false;
          }
    }

    public function setMenu ($codigo) {

      switch ($codigo) {
        case '1':
          $this->setPagina("index");
          $this->setPrincipal(1);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(0);
          break;
        case '4':
          $this->setPagina("antena");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Antenas');
          break;
          case '5':
          $this->setPagina("barrio");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Barrios');
          break;
        case '6':
          $this->setPagina("calle");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Calles');
          break;
        case '7':
          $this->setPagina("ciudad");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Ciudad');
          break;
        case '8':
          $this->setPagina("CodigoDeArea");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Código de Área');
          break;  
        case '9':
          $this->setPagina("Equipo");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Equipos');
          break;  
        case '10':
          $this->setPagina("Direccion");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Direcciones');
          break;  
        case '11':
          $this->setPagina("Nivel");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Niveles de Autorizacion');
          break;
        case '12':
          $this->setPagina("Panel");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Paneles');
          break;
        case '13':
          $this->setPagina("Dispositivo");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Dispositivos');
          break;
        case '14':
          $this->setPagina("Plan");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Planes');
          break;
        case '15':
          $this->setPagina("Site");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de los Sitios');
          break;
        case '17':
          $this->setPagina("Cliente");
          $this->setPrincipal(0);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(1);
          $this->setTitulo('Administración de Clientes');
          break;
        default:
          $this->setPagina("index");
          $this->setPrincipal(1);
          $this->setContratos(0);
          $this->setInternet(0);
          $this->setDatos(0);
          break;
      }
    }
    
    public function __construct()
    {
      //$this->autenticado = null;
      //$this->pagina = null;
      $this->usuario = new User();
      //$this->login = null;
      //$this->perfil = null;
      //$this->principal = null;
      //$this->contratos = null;
      //$this->internet = null;
      //$this->datos = null;
      $this->setMenu(1);
    }

}