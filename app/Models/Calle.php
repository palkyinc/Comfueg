<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function getCallePorNombre($calle) {
        return Calle::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$calle}%"])
            ->first();
    }

}
