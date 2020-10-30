<?php
$campoBusqueda = 'Mac Address: ';
define('PERIMISO_CREAR', 3);
define('PERIMISO_MODIFICAR', 3);
define('COLSPAN', 9);
function setDatosPorArray($elemento)
{
    if ($elemento->setDatosPorArray(['id' => $_POST['id'], 'nombre' => $_POST['nombre'], 'num_dispositivo' => $_POST['num_dispositivo'], 'mac_address' => $_POST['mac_address'], 'ip' => $_POST['ip'], 'num_antena' => $_POST['num_antena'], 'fecha_alta' => $_POST['fecha_alta'], 'fecha_baja' => $_POST['fecha_baja'], 'comentario' => $_POST['comentario']])) {
        return $elemento;
        } else {
            return false;
            }
}
$clase = 'Equipo';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Dispositivo </th>
                <th scope="col"> Mac Address </th>
                <th scope="col"> IP </th>
                <th scope="col"> Antena </th>
                <th scope="col"> Cambiar </th>
                <th scope="col"> Comentario </th>
                <th scope="col">';
function tablaRender($objetos, $datos)
{
    foreach ($objetos as $fila) {
        echo '<tr><th scope="row">' . $fila->getId() . '</th>';
        echo '<td>' . $fila->getNombre() . '</td>';
        echo '<td>' . $fila->getDispositvoModelo() . '</td>';
        echo '<td>' . $fila->getMac_address() . '</td>';
        echo '<td>' . $fila->getIp() . '</td>';
        echo '<td>' . $fila->getAntenaDescripcion() . '</td>';
        if ($datos->usuario->getNivelId() > 0 && $datos->usuario->getNivelId() <= PERIMISO_MODIFICAR) {
            if (!$fila->getFecha_baja()) {
                echo '<td><form action="serverRest/acciones.php" method="post" class="margenAbajo">
                                <input type="hidden" name="accion" value="1320">
                                <input type="hidden" name="idEdit" value="' . $fila->getId() . '">
                                <button class="btn btn-success">Desactivar</button>
                            </form></td>';
            } else {
                echo '<td><form action="serverRest/acciones.php" method="post" class="margenAbajo">
                                <input type="hidden" name="accion" value="1321">
                                <input type="hidden" name="idEdit" value="' . $fila->getId() . '">
                                <button class="btn btn-danger">Activar</button>
                            </form></td>';
            }
        } else {
            echo '<td>Sin permiso</td>';
        }
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
function formRender($elemento, $datos)
{
    $dispositivos = MyPdo::getElementForSelect('dispositivos', 'modelo');
    $antenas = MyPdo::getElementForSelect('antenas', 'descripcion');

?><div class="form-row">
        <div class="form-group col-md-3">
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" value="<?= $elemento->getNombre() ?>" maxlength="45" class="form-control" id="nombre">
        </div>
        <div class="form-group col-md-3">
            <label for="num_dispositivo">Dispositivo: </label>
            <select class="form-control" name="num_dispositivo" id="num_dispositivo">
                <option value="">Seleccione un Dispositivo...</option>
                <?php
                foreach ($dispositivos as $dispositivo) {
                    if ($dispositivo['id'] != $elemento->getNum_dispositivo()) {
                        echo '<option value="' . $dispositivo['id'] . '">' . $dispositivo['modelo'] . '</option>';
                    } else {
                        echo '<option value="' . $dispositivo['id'] . '" selected>' . $dispositivo['modelo'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="num_antena">Antena: </label>
            <select class="form-control" name="num_antena" id="num_antena">
                <option value="">Seleccione una Antena...</option>
                <?php
                foreach ($antenas as $antena) {
                    if ($antena['id'] != $elemento->getNum_antena()) {
                        echo '<option value="' . $antena['id'] . '">' . $antena['descripcion'] . '</option>';
                    } else {
                        echo '<option value="' . $antena['id'] . '" selected>' . $antena['descripcion'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="mac_address">Mac Address: </label>
            <?php
            if (($datos->usuario->getNivelId() > 0 && $datos->usuario->getNivelId() <= 1) || $elemento->getId() == '') {
            ?>
            <input type="text" name="mac_address" value="<?= $elemento->getMac_address() ?>" maxlength="17" class="form-control" id="mac_address">
            <?php
            } else {
            ?>
            <input type="hidden" name="mac_address" value="<?= $elemento->getMac_address() ?>" maxlength="17" class="form-control" id="mac_address">
            <div class="form-control" readonly>
                    <?= $elemento->getMac_address() ?>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="form-group col-md-3">
            <label for="ip">Ip: </label>
            <input type="text" name="ip" value="<?= $elemento->getIp() ?>" maxlength="15" class="form-control" id="ip">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="fecha_alta">Alta: </label>
            <input type="hidden" name="fecha_alta" value="<?= $elemento->getFecha_alta() ?>" id="fecha_alta">
            <div class="form-control" readonly><?= $elemento->getFecha_alta() ?></div>
        </div>
        <div class="form-group col-md-3">
            <label for="fecha_baja">Baja: </label>
            <div class="form-control" readonly><?= $elemento->getFecha_baja() ?></div>
            <input type="hidden" name="fecha_baja" value="<?= $elemento->getFecha_baja() ?>" id="fecha_baja">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="comentario">Comentario: </label>
            <textarea name="comentario" class=" form-control" id="comentario" rows="auto" cols="50"><?= $elemento->getComentario() ?></textarea>
        </div>
    </div>
<?php
}
require 'tablaBuscar.view.php';
