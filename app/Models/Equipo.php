<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Panel;
use Illuminate\Support\Facades\Crypt;

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
                $equipo = Equipo::where('ip', $ip)->first();
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
    
    public function relProducto () {
        return $this->belongsTo('App\Models\Producto', 'num_dispositivo', 'id');
    }
    public function relAntena () {
        return $this->belongsTo('App\Models\Antena', 'num_antena', 'id');
    }
    public function relContrato () {
        return $this->belongsTo('App\Models\Contrato', 'num_equipo', 'id');
    }
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

    public function getResumida()
    {
        return ($this->nombre . ', ' . $this->relProducto->modelo . ', ' . $this->mac_address);
    }

}// finde la clase
