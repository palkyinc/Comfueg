<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'paneles';

    public function relEquipo ()
    {
        return $this->belongsTo('App\Models\Equipo', 'id_equipo', 'id');
    }
    public function relSite ()
    {
        return $this->belongsTo('App\Models\Site', 'num_site', 'id');
    }
    public function relPanel ()
    {
        return $this->belongsTo('App\Models\Panel', 'panel_ant', 'id');
    }
    public function barrios () 
    {
        return $this->belongsToMany('App\Models\Barrio', 'panel_has_barrios', 'panel_id', 'barrio_id');
    }
    public function getResumida()
    {
        return $this->ssid . ', ' . $this->relEquipo->ip;
    }
}// fin de la clase
