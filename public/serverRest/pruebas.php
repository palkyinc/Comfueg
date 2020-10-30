<?php

spl_autoload_register(function ($nombre_clase) {
	require_once 'Class/' . $nombre_clase . '.Class.php';
});

MisFunciones::imprimirConPrintR($_FILES);
if (isset($_FILES['cobertura']) && $_FILES['cobertura']['error'] !== 4) {
	$tmp_name = $_FILES['cobertura']['tmp_name'];
	$CARPETA = '../img/';
	switch ($_FILES['cobertura']['type']) {
		case 'image/jpeg':
			$extension = 'jpg';
			break;
		case 'image/png':
			$extension = 'png';
			break;
		case 'image/svg+xml':
			$extension = 'svg';
			break;
		
		default:
			die;
			break;
	}
	$nombre_archivo = 'imagenDePrueba.' . $extension;
	move_uploaded_file($tmp_name, $CARPETA . $nombre_archivo);
	unlink ('../img/imagendePrueba.svg'); //Borra archivo
}


?>
<form action="" method="post" enctype="multipart/form-data">
	<div class="custom-file">
		<label class="custom-file-label" for="cobertura">Archivo de Cobretura. (Solo PNG/JPG/SVG)</label>
		<input type="file" class="custom-file-input" id="cobertura" name="cobertura">
	</div>
	<button type="submit"> Enviar</button>
</form>