<?php

namespace App\Models;

use App\Custom\GatewayMikrotik;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';

    public function relGateway()
    {
        return $this->belongsTo('App\Models\Panel', 'gateway_id', 'id');
    }

    public function getProveedoresQuantity() // tambien Next Classifier|
    {
        $proveedores = Proveedor::select('classifier')->where('estado', true)->get();
        return count($proveedores);
        dd(count($proveedores));
    }

    public function getClassifiersQuantity() // tambien Next Classifier|
    {
        $proveedores = Proveedor::select('bajada')->where('estado', true)->get();
        $total = 0;
        foreach ($proveedores as $proveedor)
        {
            $total += $proveedor->bajada;
        }
        return round($total/5120);
    }

    public function getNextInterface ()
    {
        $nextProveedor = Proveedor::where('classifier', $this->classifier + 1)->first();
        if (!$nextProveedor)
        {
            $nextProveedor = Proveedor::where('classifier', 0)->first();
        }
        return $nextProveedor;
    }

    public function reordenarClassifiers()
    {
        $proveedores = Proveedor::where('estado', true)->get();
        for ($i=0; $i < count($proveedores); $i++) { 
            $proveedores[$i]->classifier = $i;
            $proveedores[$i]->save();
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
                    if ($comment[0] == $this->id && $comment[1] == 'proveedor_id' && $comment[2] == 'B' && $value['active'] == 'true')
                    {
                        return true;
                    }
                } 
            }
		}
        return false;
    }

    public static function tieneProveedor ($interface_id)
    {
        return Proveedor::where('interface', $interface_id)->first();
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
            $interface = $apiMikro->getDatosEthernet($this->interface, $this->esVlan)['name'];
            unset($apiMikro);
            return($interface);
        }
    }
}
