<?php
spl_autoload_register(function ($nombre_clase) {
    require_once 'Class/' . $nombre_clase . '.Class.php';
});
$divResultados = '';
$formNuevo = '';
$errores = '';

if (isset($_POST['nuevo']) || isset($_POST['idEdit']) || isset($_POST['id'])) 
{
    $divResultados = 'ocultar';
    if (isset($_POST['nuevo'])) 
    {
        $elemento = new $clase();
    } elseif (isset($_POST['idEdit']))
            {
                $elemento = new $clase($_POST['idEdit']);
            } else {
                    $elemento = new $clase();
                    if (setDatosPorArray($elemento)) {
                        if (!($resultado = $elemento->guardarEnDb())) {
                            $errores = 'Error al grabar en base de datos';
                        } else {
                            $resultado['id_usuario'] = $datos->usuario->getId();
                            if (!MisFunciones::grabarAuditsPDO($resultado)) {
                                $errores = 'Error al grabar Audits';
                            }
                        }
                    } else {
                                $errores = $elemento->getErrores();
                                $elemento->setErrores();
                            }
                        if ($errores === '') {
                            echo "<script>alert('Grabado OK');window.location.replace('index.php');</script>";
                            die;
                        } else {
                            $errores = '<div class="alert alert-danger" role="alert">' . $errores . '</div>';
                        }
                    }
} else {
    $formNuevo = 'ocultar';
    $pagina = 1;
    $query = "";
    $elemento = new $clase();
    if (isset($_GET['query']) && isset($_GET['pagina'])) {
        $query = $_GET['query'];
        $pagina = $_GET['pagina'];
    }
    $paginado = (($pagina - 1) * 20);
    $where = '%' . $query . '%';
    $cantElementos = $clase::getCantElementos($query);
    $cantPaginas = ($cantElementos <= 20) ? 1 : intval(($cantElementos / 20) + 1);
    $funcionGetCollection = 'getCollection' . $clase;
    $objetos = $clase::$funcionGetCollection($where, $paginado);
    $sinResultados = (!$objetos) ? '<div class="alert alert-warning" role="alert">
      Sin resultados encontrados
    </div>' : '';
    $paginaAnt = 'index.php?pagina=' . ($pagina - 1) . '&query=' . $query;
    $paginaSig = 'index.php?pagina=' . ($pagina + 1) . '&query=' . $query;
    $mostrarPagAnt = (($pagina - 1) == 0) ? 'ocultar' : '';
    $mostrarPagSig = (($pagina + 1) <= $cantPaginas) ? '' : 'ocultar';
    $objetos = (!$objetos) ? [] : $objetos;
    if ($datos->usuario->getNivelId() > 0 && $datos->usuario->getNivelId() <= PERIMISO_CREAR) {
        $form_nuevo = '<form action="" method="post" class="margenAbajo">
                        <input type="hidden" name="nuevo" value="1">
                        <button class="btn btn-primary">Agregar ' . $datos->getPAgina() . '</button>
                    </form>';
    } else {
        $form_nuevo = 'Editar';
    }
    $thTabla .= $form_nuevo . '</th>';
}
?>

<main>
    <div class=" <?= $divResultados ?>">
        <div class="tercermenu" id="tercermenu">
            <ul>
                <li>
                    <h4><?= $titulo ?></h4>
                </li>
                <li>
                    <form class="form-inline mx-4" action="" method="GET">
                        <input type="hidden" name="pagina" value="1">
                        <label for="query" class="mx-3"><?= $campoBusqueda ?></label>
                        <input type="text" name="query" class="form-control mx-3" id="query">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>

                    </form>
                </li>
                <li>
                    <h5>Elementos encontrados: <?= $cantElementos ?></h5>
                </li>
                <li>
                    <h5> Total de páginas: <?= $cantPaginas ?></h5>
                </li>
            </ul>
        </div>

        <?= $sinResultados ?>

        <article id="resultados" class="">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover" id="tabla">
                    <caption>Listado de <?= $datos->getPagina() ?>s</caption>
                    <thead class="thead-light">
                        <tr>
                            <?= $thTabla ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        tablaRender($objetos, $datos);
                        ?>
                    </tbody>
                    <tfoot>
                        <td colspan="<?= COLSPAN ?>">
                            <nav aria-label="Page navigation example" id="paginado">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= $mostrarPagAnt ?>" id="anterior">
                                        <a class="page-link" tabindex="-1" aria-disabled="true" href="<?= $paginaAnt ?>">Anterior</a>
                                    </li>
                                    <li class="page-item">
                                        <div class="page-link" id="numPagina"><?= $pagina ?>
                                        </div>
                                    </li>
                                    <li class="page-item <?= $mostrarPagSig ?>" id="siguiente"> <a class="page-link" href="<?= $paginaSig ?>">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                            </td>
                    </tfoot>
                </table>
            </div>
        </article>
    </div>
    <article class="<?= $formNuevo ?>">
        <?= $errores ?>
        <h3>ID: <?= $elemento->getId() ?></h3>
        <form action="" method="post" enctype="multipart/form-data">

            <?php
            formRender($elemento, $datos);
            ?>
            <input type="hidden" name="id" value="<?= $elemento->getId() ?>">
            <button type="submit" class="btn btn-primary" id="enviar">Enviar</button>
            <a href="index.php" class="btn btn-primary">volver</a>
        </form>
    </article>
</main>