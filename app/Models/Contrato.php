<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;


class Contrato extends Model
{
    use HasFactory;
    
    public function relCliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'num_cliente', 'id');
    }

    public function relPlan()
    {
        return $this->belongsto('App\Models\Plan', 'num_plan', 'id');
    }

    public function relEquipo()
    {
        return $this->belongsto('App\Models\Equipo', 'num_equipo', 'id');
    }

    public function relPanel()
    {
        return $this->belongsto('App\Models\Panel', 'num_panel', 'id');
    }

    public function relDireccion()
    {
        return $this->belongsto('App\Models\Direccion', 'id_direccion', 'id');
    }

    public function relContacto()
    {
        return $this->belongsto('App\Models\Contacto', 'id_contacto', 'id');
    }
    public function inicioDateTimeLocal()
    {
        return str_split($this->dateTimeLocal($this->created_at), 16)[0];
    }
    private function dateTimeLocal($date)
    {
        $respuesta = explode('+', date("c", strtotime($date)));
        return ($respuesta[0]);
    }
}
