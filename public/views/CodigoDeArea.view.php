<?php
$campoBusqueda = 'Código de Área: ';
define('PERIMISO_CREAR', 1);
define('PERIMISO_MODIFICAR', 1);
define('COLSPAN', 5);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'codigoDeArea' => $_POST['codigoDeArea'], 'provincia' => $_POST['provincia'], 'localidades' => $_POST['localidades']]));
}
$clase = 'CodigoDeArea';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Código de Area </th>
                <th scope="col"> Provincia </th>
                <th scope="col"> Localidades </th>
                <th scope="col">';
//Seguir a partir de aca.
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getCodigoDeArea() . '</td>';
                            echo '<td>' . $fila->getProvincia() . '</td>';
                            echo '<td>' . $fila->getLocalidades() . '</td>';
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
                  <label for="codigoDeArea">Código de Área: </label>
                  <input type="text" name="codigoDeArea" value="'.$elemento->getCodigoDeArea().'" maxlength="30"  class="form-control" id="codigoDeArea">
                </div>
                <div class="form-group col-md-4">
                  <label for="provincia">Provincia: </label>
                  <input type="text" name="provincia" value="'.$elemento->getProvincia().'" maxlength="30"  class="form-control" id="provincia">
                </div>
              </div>
              
                <div class="form-group col-md-6">
                  <label for="localidades">Localidades: </label>
                  <textarea name="localidades" "class="form-control" id="localidades" rows="auto" cols="50">'.$elemento->getLocalidades().'</textarea>
                </div>
              ';
    }
    require 'tablaBuscar.view.php';