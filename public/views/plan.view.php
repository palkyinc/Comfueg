<?php
$campoBusqueda = 'Nombre: ';
define('PERIMISO_CREAR', 1);
define('PERIMISO_MODIFICAR', 1);
define('COLSPAN', 6);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'nombre' => $_POST['nombre'], 'bajada' => $_POST['bajada'], 'subida' => $_POST['subida'], 'descripcion' => $_POST['descripcion']]));
}
$clase = 'Plan';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Bajada </th>
                <th scope="col"> Subida </th>
                <th scope="col"> Descripción </th>
                <th scope="col">';
//Seguir a partir de aca.
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getNombre() . '</td>';
                            echo '<td>' . $fila->getBajada() . '</td>';
                            echo '<td>' . $fila->getSubida() . '</td>';
                            echo '<td>' . $fila->getDescripcion() . '</td>';
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
                  <input type="text" name="nombre" value="'.$elemento->getNombre().'" maxlength="45"  class="form-control" id="nombre">
                </div>
                <div class="form-group col-md-4">
                  <label for="bajada">Bajada: </label>
                  <input type="text" name="bajada" value="'.$elemento->getBajada().'" maxlength="45"  class="form-control" id="bajada">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-2">
                  <label for="subida">Subida: </label>
                  <input type="text" name="subida" value="'.$elemento->getSubida().'" maxlength="45"  class="form-control" id="subida">
                </div>
                <div class="form-group col-md-6">
                  <label for="descripcion">Descripción: </label>
                  <textarea name="descripcion" "class="form-control" id="descripcion" rows="auto" cols="50">'.$elemento->getDescripcion().'</textarea>
                </div>
              </div>
              
              ';
    }
    require 'tablaBuscar.view.php';