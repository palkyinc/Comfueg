<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Direccion;

class Calle extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function getCallePorNombre($calle) {
        return Calle::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$calle}%"])
            ->first();
    }
    public function relDireccion()
    {
        return $this->belongsToMany(App\Models\Direccion::class, 'id_calle', 'id');
    }

}
