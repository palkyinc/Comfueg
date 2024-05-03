<?php

namespace App\Models;

use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Custom\ubiquiti;
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
    public function relRouter()
    {
        return $this->belongsto('App\Models\Equipo', 'router_id', 'id');
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
    public function modificarMac ($ope) // 'ope' => 1 = Del, 0 = Add
    {
        return ubiquiti::tratarMac(
            [
                'usuario' => $this->relPanel->relEquipo->getUsuario(),
                'password' => $this->relPanel->relEquipo->getPassword(),
                'ip' => $this->relPanel->relEquipo->ip,
                'contrato' => $this->id,
                'macaddress' => $this->relEquipo->mac_address,
                'ope' => $ope
            ]);
    }
    private function openSessionGateway()
    {
        $apiMikro = GatewayMikrotik::getConnection($this->relPlan->relPanel->relEquipo->ip, $this->relPlan->relPanel->relEquipo->getUsuario(), $this->relPlan->relPanel->relEquipo->getPassword());
        if ($apiMikro) 
        {
            if ($apiMikro->checkHotspotServer($this->relPlan->relPanel->relEquipo->ip)) 
            {
                return $apiMikro;
            }
        }
        return false;
    }
    public function createContratoGateway()
    {
        switch ($this->tipo) {
            case 1:
                $ip = $this->relEquipo->ip;
                $macaddress = $this->relEquipo->mac_address;
                break;
            
            case (2 || 3):
                $ip = $this->relRouter->ip;
                $macaddress = $this->relRouter->mac_address;
                break;
            
            default:
                return 'ERROR. En tipo de Contrato, al crear contrato en Mikrotik(' . $this->relPlan->relPanel->relEquipo->ip . ')';
                break;
        }
        if ($apiMikro = $this->openSessionGateway()) 
        {
            $apiMikro->addClient([
                'name' => $ip,
                'mac-address' => $macaddress,
                'comment' => $this->id, ';contrato_id;A;addedBySlam',
                'server' => 'hotspot1',
                'list' => $this->relPlan->id
            ]);
            $apiMikro->checkDhcpServer($this->relPlan->relPanel->relEquipo->ip);
            $apiMikro->comm('/ip/dhcp-server/lease/add', [  'address' => $ip,
                                                            'mac-address' => $macaddress,
                                                            'server' => 'SlamServer',
                                                            'comment' => $this->id]);
            return 'EXITO. Contrato de ' . $this->relCliente->getNomYApe() . ' creado con Exito en Gateway(' . $this->relPlan->relPanel->relEquipo->ip . ')';
        } 
        else 
            {
                return 'ERROR al conectarse al Gateway (' . $this->relPlan->relPanel->relEquipo->ip . '): No se pudo crear.';
            }
    }
    public function changeStateContratoGateway()
    {
        if ($apiMikro = $this->openSessionGateway())
        {
            switch ($this->tipo) {
            case 1:
                $ip = $this->relEquipo->ip;
                $macaddress = $this->relEquipo->mac_address;
                break;
            
            case (2 || 3):
                $ip = $this->relRouter->ip;
                $macaddress = $this->relRouter->mac_address;
                break;
            
            default:
                return 'ERROR. En tipo de Contrato, al crear contrato en Mikrotik(' . $this->relPlan->relPanel->relEquipo->ip . ')';
                break;
        }
        $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($this->id, $clientsDataGateway, $macaddress);
            if ($this->activo)
            {
                $apiMikro->enableClient($gatewayContract);
                $respuesta = 'EXITO. Contrato de ' . $this->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            }
            else
            {
                $apiMikro->disableClient($gatewayContract);
                $respuesta = 'EXITO. Contrato de ' . $this->relCliente->getNomYApe() . ' fue deshabilitado con Exito!!';
            }
            unset($apiMikro);
        } else {
            $respuesta = 'ERROR. No se pudo realizar el cambio.';
        }
        return($respuesta);
    }
    public function removeContract()
    {
        ### quitar mac del panel
        $respuesta[] = $this->modificarMac(1);
        ### quitar contrato del gateway
        $respuesta[] = $this->removeContratoGateway();
        $this->activo = false;
        $this->baja = true;
        $this->save();
        $respuesta [] = $this->relEquipo->changeEquipoStatus(false);
        return $respuesta;
    }
    private function removeContratoGateway()
    {
        if ($apiMikro = $this->openSessionGateway($this)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($this->id, $clientsDataGateway);
            $apiMikro->removeClient($gatewayContract);
            $apiMikro->comm('/ip/dhcp-server/lease/remove', ['numbers' => $apiMikro->getIdDhcpServer($this->id)]);
            $respuesta = 'EXITO: Contrato de ' . $this->relCliente->getNomYApe() . ' fue Removido con exito del Gateway!!';
        	unset($apiMikro);
        } else 
            {
                $respuesta = 'ERROR: No se pudo realizar el cambio.';
            }
        return($respuesta);
    }
}
