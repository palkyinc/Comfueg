<?php
$campoBusqueda = 'Código Comfueg: ';
define('PERIMISO_CREAR', 2);
define('PERIMISO_MODIFICAR', 3);
define('COLSPAN', 6);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'marca' => $_POST['marca'], 'modelo' => $_POST['modelo'], 'cod_comfueg' => $_POST['cod_comfueg'], 'descripcion' => $_POST['descripcion']]));
}
$clase = 'Dispositivo';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Marca </th>
                <th scope="col"> Modelo </th>
                <th scope="col"> Código Comfueg </th>
                <th scope="col"> Descripción </th>
                <th scope="col">';
//Seguir a partir de aca.
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getMarca() . '</td>';
                            echo '<td>' . $fila->getModelo() . '</td>';
                            echo '<td>' . $fila->getCod_comfueg() . '</td>';
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
                  <label for="marca">Marca: </label>
                  <input type="text" name="marca" value="'.$elemento->getMarca().'" maxlength="45"  class="form-control" id="marca">
                </div>
                <div class="form-group col-md-4">
                  <label for="modelo">Modelo: </label>
                  <input type="text" name="modelo" value="'.$elemento->getModelo().'" maxlength="45"  class="form-control" id="modelo">
                </div>
                <div class="form-group col-md-2">
                  <label for="cod_comfueg">Código Comfueg: </label>
                  <input type="text" name="cod_comfueg" value="'.$elemento->getCod_comfueg().'" maxlength="45"  class="form-control" id="cod_comfueg">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-8">
                  <label for="descripcion">Descripción: </label>
                  <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="50">'.$elemento->getDescripcion().'</textarea>
                </div>
              </div>
              
              ';
    }
    require 'tablaBuscar.view.php';