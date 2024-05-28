<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mac_address_exception extends Model
{
    use HasFactory;

    public function relPanel()
    {
        return $this->belongsto('App\Models\Panel', 'panel_id', 'id');
    }
    public function relEquipo()
    {
        return $this->belongsto('App\Models\Equipo', 'equipo_id', 'id');
    }
}
