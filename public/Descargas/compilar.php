<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form action="compilar.php">
		<label for="archivo">Nombre del Archivo</label>
		<input type="text" name="archivo">
		<button type="submit">Enviar</button>
	</form>
</body>
</html>

<?php

	if (!$_GET['archivo']) {
		echo 'ingrese el nombre del archivo<br>'; 
	} else {
		##iterar
			##abrir el achivo de salida
		$salida = fopen($_GET['archivo'] . ".ts", w) or die ("Error al abrir archivo de salida<br>");
		$i=0;
		while (file_exists($i . '.ts')) {
			$fuente = fopen($i . '.ts', r) or die ("Error al abrir archivo sitio" . $i . "<br>");
			while ( !feof($fuente) ) { 
				fwrite ($salida, fread($fuente, 65536));
			}
			fclose($fuente) or die ("Error al cerrar archivo fuente: " . $i . "<br>");	
			##iterar: si existe abrir archivo a leer 
			    ## leer y copiar al archivo de salida
				## cerrar el archivo a leer
			##fin iterar
			$i++;
		}
		## cierro archivo salida
		fclose($salida) or die ("Error al cerrar archivo de salida<br>");
		echo 'Archivo de salida:' . $_GET['archivo'] . ".ts, fue cerrado<br>";
		## borro archivos fuentes.
		for (; $i >= 0 ; $i--) { 
			if (file_exists($i . '.ts')){
				unlink($i . '.ts');
			} else {
				echo 'No existe el archivo: ' . $i . '.ts<br>';
			}
		}
		echo 'Fin.-<br>';

}