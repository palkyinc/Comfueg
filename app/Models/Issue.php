<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Issue_title;
use App\Models\Issues_update;
use App\Mail\TicketNuevo;
use App\Mail\TicketActualizado;
use App\Mail\TicketCerrado;

class Issue extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo_id',
        'descripcion',
        'asignado_id',
        'creator_id',
        'cliente_id',
        'contrato_id',
        'closed'
    ];

    ##----------
    ### $tipo = 1 ->nuevo
    ### $tipo = 2 ->actualizacion
    ### $tipo = 3 ->cerrado
    ##----------
    public function enviarMail ($tipo, $auto = false)
    {
        $arrayAsignado = [$this->relAsignado->email];
        $arrayViewers = $this->getArrayViewers();
        if($auto || auth()->user()->id != $this->asignado_id)
        {
            $this->subEnviarEmail($arrayAsignado, $arrayViewers, $tipo);
        }
        elseif ($arrayViewers != [])
            {
                $this->subEnviarEmail($arrayViewers, [], $tipo);      
            }
    }

    private function subEnviarEmail ($arrayAsignado, $arrayViewers, $tipo)
    {
        switch ($tipo)
        {
            case 1:
                $toSend = new TicketNuevo($this);
                break;
            case 2:
                $toSend = new TicketActualizado($this);
                break;
            case 3:
                $toSend = new TicketCerrado($this);
                break;
            default:
                return ('error en tipo de mail');
                break;
        }
        try
        {
            Mail::to($arrayAsignado)->cc($arrayViewers)->send($toSend);
        }catch (Exception $e)
            {
                echo 'Error al enviar correo<br>';
                echo $e . '<br>';
                echo '<a href="/inicio" class="btn btn-primary">Inicio</a>';
            }
    }

    public function issues_update ()
    {
        return $this->hasMany(issues_update::class, 'issue_id');
    }

    public function cant_updates()
    {
        return Issues_update::where('issue_id', $this->id)->count();
    }

    private function getArrayViewers ()
    {
        foreach ( (($this->viewers != 'null' && $this->viewers != null) ? json_decode($this->viewers) :[]) as $value)
        {
            $usuario = User::find($value);
            $respuesta[] = $usuario->email;
        }
        return isset($respuesta) ? $respuesta : [];
    }
    
    public function scopeAsignado($query, $asignado)
    {
        if($asignado)
            return $query->where('asignado_id', $asignado);
    }
    
    public function scopeContrato($query, $contrato)
    {
        if($contrato)
            return $query->where('contrato_id', $contrato);
    }
    
    public function scopeCliente($query, $cliente)
    {
        if($cliente)
            return $query->where('cliente_id', $cliente);
    }
    
    public function scopeAbierta($query, $abierta)
    {
        if($abierta == 'on')
            return $query->where('closed', false);
    }
    
    public function relTitle()
    {
        return $this->belongsTo(Issue_title::class, 'titulo_id', 'id');
    }
    
    public function relAsignado()
    {
        return $this->belongsTo(User::class, 'asignado_id', 'id');
    }
    
    public function relCreator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    
    public function relCliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    
    public function relContrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id', 'id');
    }

    public function getVencida($status = false)
    {
        if ($this->closed) {
            if ($status) {
                $cerrada = new DateTime($this->updated_at);
                return $this->estavencida($cerrada, $status);
            } else {
                return 'Cerrada: ' . date('d-M-Y', strtotime($this->updated_at));
            }
        } else {
            date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
            $hoy =  new DateTime();
            return $this->estavencida($hoy, $status);
        }
    }

    private function estaVencida ($hoy, $status) {
        $tmr = new DateTime($this->created_at);
        $tmr->modify('+' . $this->relTitle->tmr . ' day');
        if ($tmr->format('w') == 0) {
            $tmr->modify('+1 day');
        } elseif ($tmr->format('w') == 6) {
            $tmr->modify('+2 day');
        }
        $interval = $tmr->diff($hoy);
        if ($interval->invert) {
            if ($status) {
                return false;
            } else {
                return 'Vence en: ' . $interval->days . 'día/s';
            }
        }else {
            if ($status) {
                return true;
            } else {
                return 'Venció hace: ' . $interval->days . 'día/s';
            }
        }
    }

    public function obtenerDominio ()
    {
        return env('DOMINIO_COMFUEG');
    }
    
}
