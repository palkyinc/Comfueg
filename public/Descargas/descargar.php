<?php 
echo $_GET['sitio'] . '<br>';
echo $_GET['inicio'] . '<br>';
echo $_GET['final'] . '<br>';
if (($sitio = $_GET['sitio']) && ($inicio = $_GET['inicio']) != '' && ($final = $_GET['final']) ) {
	$sitio = explode('0.ts?', $sitio);
	$sitio[1] = '.ts?' . $sitio[1];
	for ($i= $inicio; $i <= $final ; $i++) { 
		$salida = fopen($i . ".ts", w) or die ("Error al abrir archivo" . $i . "<br>");
		$archivo = fopen($sitio[0] . $i . $sitio[1], r) or die ("Error al abrir archivo sitio" . $i . "<br>");
		while ( !feof($archivo) ) { //65536 
			fwrite ($salida, fread($archivo, 65536));
		}
		fclose($archivo) or die ("Error al cerrar archivo sitio" . $i . "<br>");
		fclose($salida) or die ("Error al cerrar archivo" . $i . "<br>");
	}
	echo "finalizado<br>";
}else {
	echo 'Faltan datos!!!<br>';
}
echo '<a href="index.html" title="inicio"> volver al inicio</a>';