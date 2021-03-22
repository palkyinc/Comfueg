<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function relCodAreaTel () {
        return $this->belongsTo('App\Models\CodigoDeArea', 'cod_area_tel', 'id');
    }
    public function relCodAreaCel () {
        return $this->belongsTo('App\Models\CodigoDeArea', 'cod_area_cel', 'id');
    }

    public function getNomYApe ()
    {
        return $this->apellido . ', ' . $this->nombre;
    }
}// fin de la clase
