<?php
$campoBusqueda = 'SSID: ';
define('PERIMISO_CREAR', 2);
define('PERIMISO_MODIFICAR', 2);
define('COLSPAN', 10);
function setDatosPorArray($elemento)
{
    $cobertura = '';
    $errores = '';
    $archivo_borrar = Panel::getNombreArchivoCobertura($elemento->getId());
    if ($elemento->setDatosPorArray(['id' => $_POST['id'], 'ssid' => $_POST['ssid'], 'rol' => $_POST['rol'], 'id_equipo' => $_POST['id_equipo'], 'num_site' => $_POST['num_site'], 'panel_ant' => $_POST['panel_ant'], 'activo' => $_POST['activo'], 'cobertura' => $cobertura, 'comentario' => $_POST['comentario']], $errores)){
        if (isset($_FILES['cobertura']) && $_FILES['cobertura']['error'] !== 4) {
            $tmp_name = $_FILES['cobertura']['tmp_name'];
            switch ($_FILES['cobertura']['type']) {
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/svg+xml':
                    $extension = 'svg';
                    break;

                default:
                    $elemento->setErrores('Archivo adjunto no es una imagen <br>');
                    return false;
                    break;
            }
            $nombre_archivo = $elemento->getSsid() . '.' . $extension;
            if ($archivo_borrar && $archivo_borrar != 'sinMapa.svg') {
                unlink (Panel::getCarpeta() . $archivo_borrar);
            }
            move_uploaded_file($tmp_name, Panel::getCarpeta() . $nombre_archivo);
            $elemento->setCobertura($nombre_archivo);
        }else {
                if (!$archivo_borrar) 
                {
                $elemento->setCobertura('sinMapa.svg');
                }
              }
        return true;
    }else   {
            return false;
            }
}
$clase = 'Panel';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> SSID </th>
                <th scope="col"> Rol </th>
                <th scope="col"> Equipo </th>
                <th scope="col"> Sitio </th>
                <th scope="col"> Panel Ant </th>
                <th scope="col"> Estado </th>
                <th scope="col"> Cobertura </th>
                <th scope="col"> Comentario </th>
                <th scope="col">';
function tablaRender($objetos, $datos)
{
    foreach ($objetos as $fila) {
        echo '<tr><th scope="row">' . $fila->getId() . '</th>';
        echo '<td>' . $fila->getSsid() . '</td>';
        echo '<td>' . $fila->getRol() . '</td>';
        echo '<td>' . $fila->getEquipoNombre(1) . '</td>';
        echo '<td>' . $fila->getSiteNombre() . '</td>';
        echo '<td>' . $fila->getPanelAntEquipo(1) . '</td>';
        if ($datos->usuario->getNivelId() > 0 && $datos->usuario->getNivelId() <= PERIMISO_MODIFICAR) {
            if ($fila->getActivo()) {
                echo '<td><form action="serverRest/acciones.php" method="post" class="margenAbajo">
                                <input type="hidden" name="accion" value="1420">
                                <input type="hidden" name="idEdit" value="' . $fila->getId() . '">
                                <button class="btn btn-success">Desactivar</button>
                            </form></td>';
            } else {
                echo '<td><form action="serverRest/acciones.php" method="post" class="margenAbajo">
                                <input type="hidden" name="accion" value="1421">
                                <input type="hidden" name="idEdit" value="' . $fila->getId() . '">
                                <button class="btn btn-danger">Activar</button>
                            </form></td>';
            }
        } else {
            echo '<td>Sin permiso</td>';
        }
        echo '<td>' . $fila->getCobertura() . '</td>';
        echo '<td>' . $fila->getComentario() . '</td>';
        if ($datos->usuario->getNivelId() > 0 && $datos->usuario->getNivelId() <= PERIMISO_MODIFICAR) {
            echo '<td><form action="" method="post" class="margenAbajo">
                            <input type="hidden" name="idEdit" value="' . $fila->getId() . '">
                            <button class="btn"><img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                            </button>
                        </form></td></tr>';
        } else {
            echo '<td>Sin permiso</td></tr>';
        }
    }
}
function formRender($elemento)
{
    $equipos = Panel::getEquipoNoInsertados();
    $sitios = MyPdo::getElementForSelect('sites', 'nombre');
    $paneles = MyPdo::getElementForSelect('paneles', 'id_equipo', 'activo');
    
    
?><div class="form-row">
        <div class="form-group col-md-3">
            <label for="ssid">SSID: </label>
            <input type="text" name="ssid" value="<?= $elemento->getSsid() ?>" maxlength="15" class="form-control" id="ssid">
            <input type="hidden" name="activo" value="<?= $elemento->getActivo() ?>" class="form-control" id="activo">
        </div>
        <div class="form-group col-md-3">
            <label for="rol">Rol: </label>
            <select class="form-control" name="rol" id="rol">
                <option value="">Seleccione un Rol...</option>
                <?php
                foreach ($elemento->getRoles() as $key => $rol) {
                    if ($key != $elemento->getRol()) {
                        echo '<option value="' . $key . '">' . $rol . '</option>';
                    } else {
                        echo '<option value="' . $key . '" selected>' . $rol . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="id_equipo">Equipo: </label>
            <select class="form-control" name="id_equipo" id="id_equipo">
                <option value="">Seleccione un Equipo...</option>
                <?php
                $seleccionado = null;
                foreach ($equipos as $dato) {
                    if ($dato['fecha_baja'] === null) {
                        $equipo = new Equipo($dato['id']);
                        if ($dato['id'] != $elemento->getId_equipo()) {
                            echo '<option value="' . $dato['id'] . '">' . $equipo->getNombre() . '->' . $equipo->getIp() . '</option>';
                        } else {
                            $seleccionado = 1;
                            echo '<option value="' . $dato['id'] . '" selected>' . $equipo->getNombre() . '->' . $equipo->getIp() . '</option>';
                        }
                    }
                }
                if (!$seleccionado) {
                    $equipo = new Equipo($elemento->getId_equipo());
                    echo '<option value="' . $equipo->getId() . '" selected>' . $equipo->getNombre() . '->' . $equipo->getIp() . '</option>';
                }
                            
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="num_site">Sitio: </label>
            <select class="form-control" name="num_site" id="num_site">
                <option value="">Seleccione un Sitio...</option>
                <?php
                foreach ($sitios as $sitio) {
                    if ($sitio['id'] != $elemento->getNum_site()) {
                        echo '<option value="' . $sitio['id'] . '">' . $sitio['nombre'] . '</option>';
                    } else {
                        echo '<option value="' . $sitio['id'] . '" selected>' . $sitio['nombre'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="panel_ant">Panel Anterior: </label>
            <select class="form-control" name="panel_ant" id="panel_ant">
                <option value="">Wispro...</option>
                <?php
                foreach ($paneles as $panel) {
                    if ($panel['activo'] == 1) {
                        $equipo = new Equipo($panel['id_equipo']);
                        if ($panel['id'] != $elemento->getPanel_ant()) {
                            echo '<option value="' . $panel['id'] . '">' . $equipo->getNombre() . '->' . $equipo->getIp() . '</option>';
                        } else {
                            echo '<option value="' . $panel['id'] . '" selected>' . $equipo->getNombre() . '->' . $equipo->getIp() . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-9">
            <div class="custom-file">
                <label for="cobertura">Archivo de Cobertura: (Solo PNG/JPG/SVG)</label>
                <input type="file" class="form-control-file" id="cobertura" name="cobertura">
            </div>
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="comentario">Comentario: </label>
            <textarea name="comentario" class="form-control" id="comentario" rows="auto" cols="50"><?= $elemento->getComentario() ?></textarea>
        </div>
    </div>

<?php
}
require 'tablaBuscar.view.php';