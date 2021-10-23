<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
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

    ##----------
    ### $tipo = 1 ->nuevo
    ### $tipo = 2 ->actualizacion
    ### $tipo = 3 ->cerrado
    ##----------
    public function enviarMail ($tipo)
    {
        $arrayAsignado = [$this->relAsignado->email];
        $arrayViewers = $this->getArrayViewers();
        switch ($tipo) {
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
        //dd($respuesta);
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

    public function getVencida()
    {
        return 'A Implementar';
    }

    public function obtenerDominio ()
    {
        return env('DOMINIO_COMFUEG');
    }
    
}
