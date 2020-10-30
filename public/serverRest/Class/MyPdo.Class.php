<?php

class MyPdo 
{
	static private $db;
	private const PDO_DSN = 'mysql:host=127.0.0.1;dbname=slam';
	private const PDO_USERNAME = 'phpuser';
	private const PDO_PASSWORD = 'palito';
	/**
	 * @return PDO
	 */
	//ERRMODE_EXCEPTION, ERRMODE_WARNING (desarrollo), ERRMODE_SILENT (final cliente).
	public static function getConnection() {
		if (!isset(self::$db)) {
			self::$db = new PDO (self::PDO_DSN, self::PDO_USERNAME, self::PDO_PASSWORD);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		}
		return self::$db;
	}

	public static function getStatement($sql) {
		return self::getConnection()->prepare($sql);
	}
	public static function getElementForSelect ($tabla, $campo, $campo2 = "") {
		if ($campo2 === "") {
			$consulta = "SELECT id, $campo FROM $tabla ORDER BY $campo";
		} else {
			$consulta = "SELECT id, $campo, $campo2 FROM $tabla ORDER BY $campo";
		}
		$stmt = self::getStatement($consulta);
		$stmt->execute();
    	if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll();
        }
      	return false;  
	}
	public static function checkCod_comfueg ($codigo, $tabla) {
		$consulta = "SELECT id FROM $tabla WHERE cod_comfueg = :codigo";
		$stmt = self::getStatement($consulta);
		$stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
		$stmt->execute();
    	if ($stmt->rowCount() === 1) {
			return ($stmt->fetch()['id']);
        }
      	return false;
	}
}
/* EJEMPLO DE TRY AND CATCH
try {
	$sql = "SELECT id, nom_ape FROM usuario";
		$db = MyPdo::getConnection();
        foreach ($db->query($sql) as $row)
            {
            print $row['id'] .' - '. $row['nom_ape'] . '<br />';
            }
}
catch (PDOException $e) {
	echo $e->getMessage();
}
*/