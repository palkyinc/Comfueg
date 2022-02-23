<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function getResumida () {
        return $this->marca . ', ' . $this->modelo . ', ' . $this->descripcion; 
    }
}
