<?php
$campoBusqueda = 'Código Comfueg: ';
define('PERIMISO_CREAR', 2);
define('PERIMISO_MODIFICAR', 3);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'descripcion' => $_POST['descripcion'], 'cod_comfueg' => $_POST['cod_comfueg']]));
}
$clase = 'Antena';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Descripción </th>
                <th scope="col"> Código Comfueg </th>
                <th scope="col">';
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getDescripcion() . '</td>';
                            echo '<td>' . $fila->getCod_comfueg() . '</td>';
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
      //MisFunciones::imprimirConPrintR($elemento); die;
        echo '<div class="form-row">
                <div class="form-group col-md-6">
                  <label for="descripcion">Descripción: </label>
                  <input type="text" name="descripcion" value="'.$elemento->getDescripcion().'" maxlength="30"  class="form-control" id="descripcion">
                </div>
                <div class="form-group col-md-4">
                  <label for="cod_comfueg">Código Comfueg: </label>
                    <input type="text" name="cod_comfueg" value="'.$elemento->getCod_comfueg().'" maxlength="45" id="cod_comfueg" class="form-control">
                </div>
              </div>';
    }
    require 'tablaBuscar.view.php';