<?php
$campoBusqueda = 'Apellido: ';
define('PERIMISO_CREAR', 4);
define('PERIMISO_MODIFICAR', 4);
define('COLSPAN', 6);
$clase = 'Cliente';
function setDatosPorArray($elemento)
{
    if ($elemento->setDatosPorArray(['id' => $_POST['identificador'], 'nombre' => $_POST['nombre'], 'apellido' => $_POST['apellido'], 'cod_area_tel' => $_POST['cod_area_tel'], 'telefono' => $_POST['telefono'], 'cod_area_cel' => $_POST['cod_area_cel'], 'celular' => $_POST['celular'], 'email' => $_POST['email'], 'hash_email' => $_POST['hash_email'], 'email_verificated' => $_POST['email_verificated'], 'nuevo' => $_POST['alta'] ])) {
        return $elemento;
    } else {
        return false;
    }
}
$thTabla = '<th scope="col"> Genesys </th>
            <th scope="col"> APELLIDO, Nombre </th>
            <th scope="col"> Telefono </th>
            <th scope="col"> Celular </th>
            <th scope="col"> Email </th>
            <th scope="col">';

function tablaRender($objetos, $datos)
{
    foreach ($objetos as $fila) {
        echo '<tr><th scope="row">' . $fila->getId() . '</th>';
        echo '<td>' . $fila->getNomyApe() . '</td>';
        echo '<td>' . $fila->getNumTelefono() . '</td>';
        echo '<td>' . $fila->getNumCelular() . '</td>';
        if ($datos->usuario->getNivelId() > 0 && $datos->usuario->getNivelId() <= PERIMISO_MODIFICAR) 
        {
            if ($fila->getEmail()) 
            {
                if  ($fila->getEmail_verificated()) {
                    echo '<td>' . $fila->getEmail() . '</td>';    
                }else   {
                        echo '<td><form action="serverRest/acciones.php" method="post" class="margenAbajo">
                        <input type="hidden" name="accion" value="1520">
                        <input type="hidden" name="idEdit" value="' . $fila->getId() . '">
                        <button class="btn btn-danger">' . $fila->getEmail() . '</button>
                        </form></td>';
                        }
            }else   {
                    echo '<td></td>';    
                    }
        } else  {
                echo '<td>' . $fila->getEmail() . '</td>';
                }
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
    $nuevo = (isset($_POST['nuevo'])) ? 1 : 0;
    $codigos = MyPdo::getElementForSelect('codigosdearea', 'codigoDeArea');

?>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="identificador"></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">ID GENESYS</span>
                </div>
                <input type="text" name="identificador" value="<?= $elemento->getid() ?>" id="identificador" maxlength="8" class="form-control">
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="apellido">Apellido: </label>
            <input type="text" name="apellido" value="<?= $elemento->getApellido() ?>" maxlength="45" class="form-control" id="apellido">
        </div>
        <div class="form-group col-md-2">
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" value="<?= $elemento->getNombre() ?>" maxlength="45" class="form-control" id="nombre">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="cod_area_tel">Código área teléfono: </label>
            <select class="form-control" name="cod_area_tel" id="cod_area_tel">
                <option value="154">2964</option>
                <?php
                foreach ($codigos as $codigo) {
                    if ($codigo['id'] != $elemento->getCod_area_tel()) {
                        echo '<option value="' . $codigo['id'] . '">' . $codigo['codigoDeArea'] . '</option>';
                    } else {
                        echo '<option value="' . $codigo['id'] . '" selected>' . $codigo['codigoDeArea'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="telefono">Teléfono: </label>
            <input type="text" name="telefono" value="<?= $elemento->getTelefono() ?>" maxlength="8" class="form-control" id="telefono">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="cod_area_cel">Código área celular: </label>
            <select class="form-control" name="cod_area_cel" id="cod_area_cel">
                <option value="154">2964</option>
                <?php
                foreach ($codigos as $codigo) {
                    if ($codigo['id'] != $elemento->getCod_area_cel()) {
                        echo '<option value="' . $codigo['id'] . '">' . $codigo['codigoDeArea'] . '</option>';
                    } else {
                        echo '<option value="' . $codigo['id'] . '" selected>' . $codigo['codigoDeArea'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="celular">Celular: </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">15</span>
                </div>
                <input type="text" name="celular" value="<?= $elemento->getCelular() ?>" maxlength="8" class="form-control" id="celular">
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="email">Email: </label>
            <input type="email" name="email" value="<?= $elemento->getEmail() ?>" id="email" maxlength="45" class="form-control">
        </div>
    </div>
    <input type="hidden" name="hash_email" value="<?= $elemento->getHash_email() ?>">
    <input type="hidden" name="email_verificated" value="<?= $elemento->getEmail_verificated() ?>">
    <input type="hidden" name="alta" value="<?= $nuevo ?>">
<?php
}
require 'tablaBuscar.view.php';
