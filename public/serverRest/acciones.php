<?php
spl_autoload_register(function ($nombre_clase) {
    require_once 'Class/' . $nombre_clase . '.Class.php';
});
$idEdit = (isset($_POST['idEdit'])) ? $_POST['idEdit'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
if ($idEdit && $accion){
    switch ($accion) {
        case '1320':
            $entidad = new Equipo($idEdit);
            $entidad->setDesactivar();
            break;
        case '1321':
            $entidad = new Equipo($idEdit);
            $entidad->setActivar();
            //MisFunciones::imprimirConPrintR($entidad); die;
            break;
        case '1420':
            $entidad = new Panel($idEdit);
            $entidad->setDesactivar();
            break;
        case '1421':
            $entidad = new Panel($idEdit);
            $entidad->setActivar();
            //MisFunciones::imprimirConPrintR($equipo); die;
            break;
        case '1520':
            MisFunciones::imprimirConPrintR($idEdit);die;
            //crear un objeto tipo Cliente idEdit
            // llamar metodo confirmarEmail.
            break;
        
        default:
            # code...
            break;
    }
}
header('Location: ../index.php');