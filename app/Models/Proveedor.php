<?php

namespace App\Models;

use App\Custom\GatewayMikrotik;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use DateTime;


class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';

    public function relGateway()
    {
        return $this->belongsTo('App\Models\Panel', 'gateway_id', 'id');
    }

    public function getProveedoresQuantity() ## tambien Next Classifier|
    {
        $proveedores = Proveedor::select('classifier')->where('estado', true)->where('gateway_id', $this->gateway_id)->get();
        return count($proveedores);
    }

    public function getClassifiersQuantity() ## tambien Next Classifier|
    {
        $proveedores = Proveedor::select('bajada')->where('estado', true)->where('gateway_id', $this->gateway_id)->get();
        $total = 0;
        foreach ($proveedores as $proveedor)
        {
            $total += $proveedor->bajada;
        }
        return round($total/$this->relGateway->div_classifier);
    }

    public function getNextInterface ()
    {
        $nextProveedor = Proveedor::where('classifier', $this->classifier + 1)
                                    ->where('gateway_id', $this->gateway_id)
                                    ->where('estado', true)
                                    ->first();
        if (!$nextProveedor)
        {
            $nextProveedor = Proveedor::where('classifier', 0)
                                        ->where('gateway_id', $this->gateway_id)
                                        ->where('estado', true)
                                        ->first();
        }
        return $nextProveedor;
    }

    public function reordenarClassifiers($esFailOver = false)
    {
        $proveedores = Proveedor::where('estado', true)->where('gateway_id', $this->gateway_id)->get();
        if ($esFailOver) {
        } else {
            for ($i=0; $i < count($proveedores); $i++) { 
                $proveedores[$i]->classifier = $i;
                $proveedores[$i]->save();
            }
        }
        
        $proveedores = Proveedor::where('estado', false)->where('gateway_id', $this->gateway_id)->get();
        foreach ($proveedores as $proveedor)
        {
            $proveedor->classifier = 1001;
            $proveedor->save();
        }
        
    }

    public function enLinea()
    {
        $apiMikro = GatewayMikrotik::getConnection($this->relGateway->relEquipo->ip, $this->relGateway->relEquipo->getUsuario(), $this->relGateway->relEquipo->getPassword());
        if ($apiMikro)
        {
            $apiMikro->write('/ip/route/print');
            $rtas = $apiMikro->parseResponse($apiMikro->read(false));
            foreach ($rtas as $key => $value) {
                if (isset($value['comment']))
                {
                    $comment = explode(';', $value['comment']);
                    if ($comment[0] == $this->id && $comment[1] == 'proveedor_id' && $comment[2] == 'A' && $value['active'] == 'true')
                    {
                        return true;
                    }
                } 
            }
		}
        return false;
    }

    public function enLineaSigueIgual() ### en Linea sigue igual??
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $estadoActual = $this->enLinea();
        if ( $this->en_linea != $estadoActual)
        {
            if ($this->contaOffline > 0)
            {
                  $this->contaOffline--;
                  $this->save();
            }
            else
            {
                $this->en_linea = $estadoActual;
                $estadoActual ? ($this->pasaOnline()) : ($this->pasaOffline());
                $this->save();
                return false;
            }
        }
        elseif ($this->contaOffline != 4 && $estadoActual)
        {
                $this->contaOffline = 4;
                $this->save();
        }
        return true;
    }

    private function pasaOnline ()
    {
        $this->online_date = Carbon::now()->toDateTimeString();
        $this->contaOffline = 4;
    }

    private function pasaOffline ()
    {
        $this->offline_date = Carbon::now()->toDateTimeString();
    }

    public static function tieneProveedor ($interface_id, $gateway_id)
    {
        return Proveedor::where('interface', $interface_id)->
                        where('gateway_id', $gateway_id)->
                        first();
    }

    public function reordenarTotales()
    {
        $proveedores = Proveedor::where('estado', true)->where('gateway_id', $this->gateway_id)->get();
        $tot_bajada = 0;
        $tot_subida = 0;
        foreach ($proveedores as $value) {
            $tot_bajada += $value->bajada; 
            $tot_subida += $value->subida; 
        }
        return ['bajada' => $tot_bajada, 'subida' => $tot_subida];
    }

    public function getInterfaceName ()
    {
        $apiMikro = GatewayMikrotik::getConnection($this->relGateway->relEquipo->ip, $this->relGateway->relEquipo->getUsuario(), $this->relGateway->relEquipo->getPassword());
        if ($apiMikro)
        {   
            $interface = ($apiMikro->getDatosEthernet($this->interface, $this->esVlan));
            if (isset($interface['name'])) {
                $interface = ($apiMikro->getDatosEthernet($this->interface, $this->esVlan))['name'];
            } else {
                $interface = 'Desconocido';
            }
            unset($apiMikro);
            return($interface);
        }
    }

    public function obtenerDominio ()
    {
        return env('VUEJS_VERSION');
    }

    public static function provedoresCaidos ()
    {
        return (Proveedor::where('en_linea', false)->where('estado', true)->get());
    }

    public function tiempoCaida ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $hoy = new DateTime();
        $inicio = new DateTime($this->offline_date);
        $interval = $inicio->diff($hoy);
        return( ($interval->m > 0 ? $interval->m . 'M ' : '') . 
                ($interval->d > 0 ? $interval->d . 'd ' : '') . 
                ($interval->h > 0 ? $interval->h . 'h ' : '') . 
                $interval->i . 'm');
    }
}
