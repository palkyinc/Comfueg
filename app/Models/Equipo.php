<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Panel;
use App\Models\Contrato;
use App\Models\Variable;
use App\Models\Mac_address_exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;

class Equipo extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    public static function getUserPassword($id, $ip = null)
    {
        if (!$ip)
        {
            $equipo = Equipo::find($id);
        }
        else 
            {
                $equipo = Equipo::where('ip', $ip)->where('fecha_baja', null)->first();
            }
        try {
            $equipo->usuario = ($equipo->usuario) ? Crypt::decrypt($equipo->usuario) : null;
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            dd('Error');
        }
        try {
            $equipo->password = ($equipo->password) ? Crypt::decrypt($equipo->password) : null;
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            dd($e);
        }
        return $equipo;
    }
    public static function setUserPass($id, $usuario, $password)
    {
        $equipo = Equipo::find($id);
        $equipo->usuario = Crypt::encrypt($usuario);
        $equipo->password = Crypt::encrypt($password);
        $equipo->save();
    }
    public function getPassword()
    {
        return (($this->password) ? Crypt::decrypt($this->password) : null);
    }
    public function setPassword($palabra)
    {
        $this->password = Crypt::encrypt($palabra);
        $this->save();
    }
    public function getUsuario()
    {
                return (($this->usuario) ? Crypt::decrypt($this->usuario) : null);
    }
    public function setUsuario($palabra)
    {
        $this->usuario = Crypt::encrypt($palabra);
        $this->save();
    }
    public function setUsPassInicial()
    {
        $this->setPassword(Config::get('constants.CLIENT_PASS'));
        $this->setUsuario(Config::get('constants.CLIENT_USER'));
    }
    public function relProducto ()
    {
        return $this->belongsTo('App\Models\Producto', 'num_dispositivo', 'id');
    }
    public function relPanel ()
    {
        return $this->belongsTo('App\Models\Panel', 'id_equipo', 'id');
    }
    public function relAntena ()
    {
        return $this->belongsTo('App\Models\Antena', 'num_antena', 'id');
    }
    public function relContrato ()
    {
        return $this->belongsTo(Contrato::class, 'num_equipo');
    }
    public function relContratoRouter()
    {
        return $this->belongsTo(Contrato::class, 'router_id');
    }
    public function isFree ()
    {
        if ($contrato = Contrato::where('router_id', $this->id)->first()) {
            return ['contrato_id' => $contrato->id];
        } elseif ($contrato = Contrato::where('num_equipo', $this->id)->first()) {
            return ['contrato_id' => $contrato->id];
        } elseif ($panel = Panel::where('id_equipo', $this->id)->first()) {
            return ['panel_id' => $panel->id];
        } elseif ($exception = Mac_address_exception::where('equipo_id', $this->id)->first()) {
            return ['exception_id' => $exception->id];
        }
        return false;
    } 
    ## retorna todos los equipos clientes que no se agregaron a un contrato
    public static function equiposSinAgregar ()
    {
        $equiposTodos = Equipo::get();
        $paneles = Panel::get();
        $equipos;
        foreach ($equiposTodos as $equipo) {
            $encontrado = false;
            foreach ($paneles as $unPanel) {
                if ($equipo->id == $unPanel->id_equipo) {
                    $encontrado = true;
                    continue;
                }
            }
            if (!$encontrado && !$equipo->fecha_baja) {
                $equipos[] = $equipo;
            }
        }
        return $equipos;
    }
    ## retorna true si $ip se encuentra usado en un contrato o dispositivo
    public static function ipLibrePaneles ($ip, $dispositivos) ##si $dispositivos = true => buscan en paneles sino en contratos
    {
        if ($dispositivos)
        {
            $candidatos = Panel::where('activo',true)->get();
        } else {
            $candidatos = Contrato::where('baja',false)->get();
        }
        foreach ($candidatos as $panel) {
            if ($panel->relEquipo->ip === $ip)
            {
                return false;
            }
        }
        return true;
    }
    public function setIpAuto ()
    {
        if ($this->ip != '0.0.0.0' && self::ipLibrePaneles($this->ip, true) && self::ipLibrePaneles($this->ip, true)) {
            return true;
        }
        $ip_inicial = $this->getIpInicial();
        $ip_actual = $this->getIpActual();
        $ip_final = $this->getIpFinal();
        $todaLaVuelta = false;
        if (!$ip_actual) {
            $ip_actual = $ip_inicial;
        }
        while (!self::ipLibrePaneles($ip_actual, true) || !self::ipLibrePaneles($ip_actual, false)) {
            $ip = explode('.', $ip_actual);
            $ip[3]++;
            if ($ip[3] > 255) {
                $ip[3] = 0;
                $ip[2]++;
                if ($ip[2] > 255) {
                    $ip[2] = 0;
                    $ip[1]++;
                    if ($ip[1] > 255) {
                        $ip[1] = 0;
                        $ip[0]++;
                    }
                    if ($ip[0] > 255) {
                        return false;
                    }
                }
            }
            $ip_actual = $ip[0] . '.' . $ip[1] . '.' . $ip[2] . '.' . $ip[3];
            if ($ip_actual === $ip_final) {
                $ip_actual = $ip_inicial;
                if ($todaLaVuelta)
                {
                    return false;
                }
                else
                {
                    $todaLaVuelta = true;
                }
            }
        }
        $this->ip = $ip_actual;
        $this->save();
        ## guardar $ip_actual
        $this->setIpActual($ip_actual);
        return true;
    }
    public function activarEstado ()
    {
        if ($this->fecha_baja) {
            $this->fecha_baja = null;
            $this->save();
            return true;
        } else {
            return false;
        }
        
    }
    private function getIpInicial ()
    {
        return Variable::find(1)->ip_inicial;
    }
    private function getIpActual ()
    {
        return Variable::find(1)->ip_actual;
    }
    private function setIpActual ($ip)
    {
        $variable = Variable::find(1);
        $variable->ip_actual = $ip;
        $variable->save();
    }
    private function getIpFinal ()
    {
        return Variable::find(1)->ip_final;
    }
    public function getResumida()
    {
        return ($this->nombre . ', ' . $this->relProducto->modelo . ', ' . $this->mac_address);
    }
    public function changeEquipoStatus ($darAlta)
    {
        if ($darAlta) 
        {
            $this->fecha_baja = null;
            ## Si viene con un IP verificar que no este usado
            ## sino set ip auto
            $rta = 'EXITO: Equipo ' . $this->getResumida() . ' dado de alta.';
            if ( Equipo::ipLibrePaneles($this->ip, true) && Equipo::ipLibrePaneles($this->ip, false) )
            {
                if ($this->ip === '0.0.0.0') {
                    $rta = $rta . 'EXITO: Se asigna IP Auto.';
                    return $this->setIpAuto() ? $rta : false;
                }
            }
            else {
                return false;
            }
            return $rta;
        } else
                {
                    ### BAJA
                    $this->fecha_baja = date('Y-m-d');
                    $this->ip = '0.0.0.0';
                    $rta = 'EXITO: Equipo ' . $this->getResumida() . ' dado de baja';
                }
        $this->save();
        return $rta;
    }
}## finde la clase
