<?php

require 'lib/password.php';

$password = '869ffcf8e63fa8cac01766b7c337cb2f8b746ea5';

$hash = password_hash($password, PASSWORD_BCRYPT);
$hash2 = password_hash($password, PASSWORD_BCRYPT);


echo $hash;

echo '<hr>';

echo $hash2;

echo '<hr>';

echo strlen($hash);

echo '<hr>';

echo strlen($hash2);

echo '<hr>';

if ($hash == $hash2) {
	echo 'son iguales';
} else {
	echo 'NO son iguales';
}

echo '<hr>';
	
	
	if (password_verify($password, $hash)) {
		echo 'es valido';
	} else {
		echo 'no es valido';
	}

echo '<hr>';
	
	$password = '12345678901234567890123456789012345678901234567890';

	if (password_verify($password, $hash2)) {
		echo 'es valido';
	} else {
		echo 'no es valido';
	}

echo '<hr>';
