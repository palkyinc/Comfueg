<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Mail\IncidenciaGlobal;
use App\Mail\DeudaTecnica;
use App\Mail\IncidenciaGlobalActualizacion;
use App\Mail\DeudaTecnicaActualizacion;
use App\Mail\IncidenciaGlobalCerrada;
use App\Mail\DeudaTecnicaCerrada;

class Site_has_incidente extends Model
{
    use HasFactory;
    // $tipo -> false = nuevo, 'actualizacion', 'cerrado'
    // $esDeuda -> true = es Deuda, false = no es deuda
    public function enviarMail($tipo = false, $esDeuda = false)
    {
        if (!$esDeuda)
        {
            $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.INCIDENTES_GLOBALES_MAIL_GROUP'));
        } else 
            {
            $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.DEUDAS_TECNICA_MAIL_GROUP'));
            }
        if (!$esDeuda)
        {
            switch ($tipo) 
                {
                    case false:
                        $toSend = new IncidenciaGlobal($this);
                        break;
                    
                    case 'actualizacion':
                        $toSend = new IncidenciaGlobalActualizacion($this);
                        break;
                    
                    case 'cerrado':
                        $toSend = new IncidenciaGlobalCerrada($this);
                        break;
                }
        }else 
            {
                switch ($tipo)
                    {
                        case false:
                            $toSend = new DeudaTecnica($this);
                            break;

                        case 'actualizacion':
                            $toSend = new DeudaTecnicaActualizacion($this);
                            break;

                        case 'cerrado':
                            $toSend = new DeudaTecnicaCerrada($this);
                            break;
                    }
            }
        Mail::to($arrayCorreos)->send($toSend);
        
    }
    public function obtenerDominio ()
    {
        return env('DOMINIO_COMFUEG');
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
        //$incidentes = Site_has_incidente::where('final', null)->where('tipo', 'INCIDENTE')->get();
        $incidentes = Site_has_incidente::where('final', null)->get();
        foreach ($incidentes as $incidente) {
            $incidente->archivos = Entity_has_file::getArchivosEntidad(3, $incidente->id);
        }
        return ($incidentes);
    }
    public function tiempoCaida ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $hoy = new DateTime();
        $inicio = new DateTime($this->inicio);
        if (!$this->final)
            {
                $interval = $inicio->diff($hoy);
            } else {
                $interval = $inicio->diff(new DateTime($this->final));
            }
        return(($interval->m > 0 ? $interval->m . 'M ' : '') . 
                ($interval->d > 0 ? $interval->d . 'd ' : '') . 
                ($interval->h > 0 ? $interval->h . 'h ' : '') . 
                $interval->i . 'm');
    }
    private function dateTimeLocal($date)
    {
        $respuesta = explode('+', date("c", strtotime($date)));
        return ($respuesta[0]);
    }
    public function inicioDateTimeLocal ()
    {
        return str_split($this->dateTimeLocal($this->inicio), 16)[0];
    }
    public function finalDateTimeLocal ()
    {
        return str_split($this->dateTimeLocal($this->final), 16)[0];
    }
    public function fecha_tentativaDateTimeLocal ()
    {
        return str_split($this->dateTimeLocal($this->fecha_tentativa), 16)[0];
    }

}
