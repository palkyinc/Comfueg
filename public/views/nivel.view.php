<?php
$campoBusqueda = 'Nombre: ';
define('PERIMISO_CREAR', 1);
define('PERIMISO_MODIFICAR', 1);
define('COLSPAN', 3);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'nombre' => $_POST['nombre']]));
}
$clase = 'Nivel';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Nombre </th>
                <th scope="col">';
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getNombre() . '</td>';
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
    function formRender ($elemento) {
        echo '<div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nombre">Nombre: </label>
                  <input type="text" name="nombre" value="'.$elemento->getNombre().'" maxlength="15"  class="form-control" id="nombre">
                </div>
              </div>';
    }
    require 'tablaBuscar.view.php';