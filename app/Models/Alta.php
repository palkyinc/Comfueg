<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Alta extends Model
{
    use HasFactory;

    public function relCliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function relPlan()
    {
        return $this->belongsto('App\Models\Plan', 'plan_id', 'id');
    }

    public function relDireccion()
    {
        return $this->belongsto('App\Models\Direccion', 'direccion_id', 'id');
    }

    public function getStatus ($alert = false) {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $hoy = new DateTime();
        $instalacion = new DateTime($this->instalacion_fecha);
        $interval = $instalacion->diff($hoy);
        if ($alert) {
            return $interval->invert ? true : false;
        }
        return(
                ($interval->invert ===1 ? 'Vence en ' : 'Vencido hace ') . 
                ($interval->m > 0 ? $interval->m . 'M ' : '') . 
                ($interval->d > 0 ? $interval->d . 'd ' : '') . 
                ($interval->h > 0 ? $interval->h . 'h ' : '') . 
                $interval->i . 'm'
            );
    }
}
