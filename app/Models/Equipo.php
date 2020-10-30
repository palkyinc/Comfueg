<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}// finde la clase
