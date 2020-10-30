<?php

spl_autoload_register(function ($nombre_clase) {
  require_once $nombre_clase . '.Class.php';
});

abstract class Collections
{
    static function getAll()
    {
        $stmt = DB::getStatement('SELECT * FROM categorias');
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Categoria');
        }
        return [];
    }
    public static function getCollectionAntena ($query = "") {
      $consulta = 'SELECT * FROM antenas' . $query;
      $stmt = MyPdo::getStatement($consulta);
      $stmt->execute();
      //      MisFunciones::imprimirConPrintR($stmt);
      if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Antena');
        }
      return false;
    }

}
