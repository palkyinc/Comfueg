<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function paneles()
    {
        return $this->belongsToMany(Panel::class, 'panel_has_barrios', 'barrio_id', 'panel_id');
    }
}
