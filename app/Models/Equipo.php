<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Panel;

class Equipo extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function relProducto () {
        return $this->belongsTo('App\Models\Producto', 'num_dispositivo', 'id');
    }
    public function relAntena () {
        return $this->belongsTo('App\Models\Antena', 'num_antena', 'id');
    }
    public static function equiposSinAgregar ()
    {
        $equiposTodos = Equipo::get();
        $paneles = Panel::get();
        $equipos;
        foreach ($equiposTodos as $equipo) {
            $encontrado = false;
            foreach ($paneles as $unPanel) {
                if ($equipo->id == $unPanel->id_equipo) {
                    $encontrado = true;
                    continue;
                }
            }
            if (!$encontrado && !$equipo->fecha_baja) {
                $equipos[] = $equipo;
            }
        }
        return $equipos;
    }

}// finde la clase
