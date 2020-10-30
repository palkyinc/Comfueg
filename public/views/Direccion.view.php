<?php
$campoBusqueda = 'ID de Dirección: ';
define('PERIMISO_CREAR', 4);
define('PERIMISO_MODIFICAR', 4);
define('COLSPAN', 6);
function setDatosPorArray ($elemento) {
return ($elemento->setDatosPorArray(['id' => $_POST['id'], 'id_calle' => $_POST['id_calle'], 'numero' => $_POST['numero'], 'id_barrio' => $_POST['id_barrio'], 'id_ciudad' => $_POST['id_ciudad']]));
}
$clase = 'Direccion';
$thTabla = '<th scope="col"> Id </th>
                <th scope="col"> Calle </th>
                <th scope="col"> Altura </th>
                <th scope="col"> Barrio </th>
                <th scope="col"> Ciudad </th>
                <th scope="col">';
function tablaRender ($objetos, $datos) {
        foreach ($objetos as $fila) {
                            echo '<tr><th scope="row">' . $fila->getId() . '</th>';
                            echo '<td>' . $fila->getNombreCalle() . '</td>';
                            echo '<td>' . $fila->getNumero() . '</td>';
                            echo '<td>' . $fila->getNombreBarrio() . '</td>';
                            echo '<td>' . $fila->getNombreCiudad() . '</td>';
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
      $barrios = MyPdo::getElementForSelect('barrios', 'nombre');
      $calles = MyPdo::getElementForSelect('calles', 'nombre');
      $ciudades = MyPdo::getElementForSelect('ciudades', 'nombre');
        ?><div class="form-row">
                <div class="form-group col-md-3">
                  <label for="id_calle">Calle: </label>
                    <select class="form-control" name="id_calle" id="id_calle">
                      <option value="">Seleccione una Calle...</option>
                      <?php 
                        foreach ($calles as $calle) {
                          if ($calle['id'] != $elemento->getIdCalle()) {
                            echo '<option value="' . $calle['id'] . '">' . $calle['nombre'] . '</option>';
                          } else {
                            echo '<option value="' . $calle['id'] . '" selected>' . $calle['nombre'] . '</option>';
                          }
                        } 
                      ?> 
                      </select>
                </div>
                <div class="form-group col-md-3">
                  <label for="numero">Altura: </label>
                  <input type="text" name="numero" value="<?=$elemento->getNumero()?>" maxlength="30"  class="form-control" id="numero">
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="id_barrio">Barrio: </label>
                  <select class="form-control" name="id_barrio" id="id_barrio">
                  <option value="">Seleccione un Barrio...</option>
                  <?php 
                    foreach ($barrios as $barrio) {
                      if ($barrio['id'] != $elemento->getIdBarrio()) {
                        echo '<option value="' . $barrio['id'] . '">' . $barrio['nombre'] . '</option>';
                      } else {
                        echo '<option value="' . $barrio['id'] . '" selected>' . $barrio['nombre'] . '</option>';
                      }
                    } 
                  ?> 
                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label for="id_ciudad">Ciudad: </label>
                    <select class="form-control" name="id_ciudad" id="id_barrio">
                      <option value="1">Rio Grande</option>
                      <?php 
                        foreach ($ciudades as $ciudad) {
                          if ($ciudad['id'] != $elemento->getIdCiudad()) {
                            echo '<option value="' . $ciudad['id'] . '">' . $ciudad['nombre'] . '</option>';
                          } else {
                            echo '<option value="' . $ciudad['id'] . '" selected>' . $ciudad['nombre'] . '</option>';
                          }
                        } 
                      ?> 
                      </select>
                </div>
              </div>
    <?php
    }
    require 'tablaBuscar.view.php';