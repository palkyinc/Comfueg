<?php
$campoBusqueda = 'Nombre: ';
define('PERIMISO_CREAR', 1);
define('PERIMISO_MODIFICAR', 1);
define('COLSPAN', 5);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'nombre' => $_POST['nombre'], 'descripcion' => $_POST['descripcion'], 'coordenadas' => $_POST['coordenadas']]));
}
$clase = 'Site';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Descripcion </th>
                <th scope="col"> Coordenadas </th>
                <th scope="col">';
//Seguir a partir de aca.
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getNombre() . '</td>';
                            echo '<td>' . $fila->getDescripcion() . '</td>';
                            echo '<td>' . $fila->getCoordenadas() . '</td>';
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
                <div class="form-group col-md-2">
                  <label for="nombre">Nombre: </label>
                  <input type="text" name="nombre" value="'.$elemento->getNombre().'" maxlength="30"  class="form-control" id="nombre">
                </div>
                <div class="form-group col-md-4">
                  <label for="coordenadas">Coordenadas: </label>
                  <input type="text" name="coordenadas" value="'.$elemento->getcoordenadas().'" maxlength="60"  class="form-control" id="coordenadas">
                </div>
              </div>
              
                <div class="form-group col-md-6">
                  <label for="descripcion">Descripción: </label>
                  <textarea name="descripcion" "class="form-control" id="descripcion" rows="auto" cols="50">'.$elemento->getDescripcion().'</textarea>
                </div>
              ';
    }
    require 'tablaBuscar.view.php';