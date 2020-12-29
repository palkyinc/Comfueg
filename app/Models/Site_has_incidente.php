<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Mail\IncidenciaGlobal;
use App\Mail\IncidenciaGlobalActualizacion;
use App\Mail\IncidenciaGlobalCerrada;

class Site_has_incidente extends Model
{
    use HasFactory;
    
    public function enviarMail($tipo = null)
    {
        $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.INCIDENTES_GLOBALES_MAIL_GROUP'));
        if ($tipo === null)
        {
            Mail::to($arrayCorreos)->send(new IncidenciaGlobal($this));
        }else if ($tipo == 'actualizacion')
            {
                Mail::to($arrayCorreos)->send(new IncidenciaGlobalActualizacion($this));
            } elseif ($tipo == 'cerrado')
            {
                Mail::to($arrayCorreos)->send(new IncidenciaGlobalCerrada($this));
            }
    }
    public function obtenerDominio ()
    {
        return Config::get('constants.DOMINIO_COMFUEG');
    }
    public function incidente_has_mensaje ()
    {
        return $this->hasMany(Incidente_has_mensaje::class, 'incidente_id');
    }
    public function relPanel()
    {
        return $this->belongsTo(Panel::class, 'afectado', 'id');
    }
    public function relUser()
    {
        return $this->belongsTo('App\Models\User', 'user_creator', 'id');
    }
    public function crearNombre()
    {
        return(date("Ymd:Hi", strtotime($this->inicio)));
    }
    public static function incidentesAbiertos ()
    {
        return Site_has_incidente::where('final', null)->where('tipo', 'INCIDENTE')->get();
    }
    public function tiempoCaida ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $hoy = new DateTime();
        $inicio = new DateTime($this->inicio);
        $interval = $inicio->diff($hoy);
        return($interval->d . 'd ' . $interval->h . 'h ' . $interval->i . 'm');
    }
    private function dateTimeLocal($date)
    {
        $respuesta = explode('+', date("c", strtotime($date)));
        return ($respuesta[0]);

    }
    public function inicioDateTimeLocal ()
    {
        return $this->dateTimeLocal($this->inicio);
    }
    public function finalDateTimeLocal ()
    {
        return $this->dateTimeLocal($this->final);
    }

}
