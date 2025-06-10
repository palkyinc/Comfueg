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
    public function modificarMac ($ope) ### 'ope' =>> 1 = Del, 0 = Add
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
            
            case (2):
                $ip = $this->relRouter->ip;
                $macaddress = $this->relRouter->mac_address;
                break;
            
            case (3):
                $ip = $this->relRouter->ip;
                $macaddress = $this->relRouter->mac_address;
                break;
            
            default:
                $ip = $this->relEquipo->ip;
                $macaddress = $this->relEquipo->mac_address;
                break;
        }
        if ($apiMikro = $this->openSessionGateway()) 
        {
            ### Programa contrato en MIkreotik
            $apiMikro->addClient([
                'name' => $ip,
                'mac-address' => $macaddress,
                'comment' => $this->id, ';contrato_id;A;addedBySlam',
                'server' => 'hotspot1',
                'list' => $this->relPlan->id
            ]);
            
            ### Programa DHCP server para equipo y/o router
            $apiMikro->checkDhcpServer($this->relPlan->relPanel->relEquipo->ip);
            if ($this->tipo === 1 || $this->tipo === 0 || $this->tipo === 2)
            {
                $apiMikro->addClientDhcp([  'address' => $this->relEquipo->ip,
                                            'mac-address' => $this->relEquipo->mac_address,
                                            'server' => 'SlamServer',
                                            'comment' => $this->id]);
            }
            if ($this->tipo === 2 || $this->tipo === 3)
            {
                $apiMikro->addClientDhcp([  'address' => $this->relRouter->ip,
                                            'mac-address' => $this->relRouter->mac_address,
                                            'server' => 'SlamServer',
                                            'comment' => $this->id]);
            }
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
                $ip = $this->relEquipo->ip;
                $macaddress = $this->relEquipo->mac_address;
                break;
        }
        $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($this->id, $clientsDataGateway, $macaddress);
            if (!$this->activo)
            {
                $apiMikro->enableClient($gatewayContract);
                $this->activo = true;
                $respuesta = 'EXITO. Contrato de ' . $this->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            }
            else
            {
                $apiMikro->disableClient($gatewayContract);
                $this->activo = false;
                $respuesta = 'EXITO. Contrato de ' . $this->relCliente->getNomYApe() . ' fue deshabilitado con Exito!!';
            }
            unset($apiMikro);
            $this->save();
        } else {
            $respuesta = 'ERROR. No se pudo realizar el cambio.';
        }
        return($respuesta);
    }
    public function removeContract()
    {
        ### quitar mac del panel solo
        if ($this->num_equipo !== 157) {
            $respuesta[] = $this->modificarMac(1);
        }
        ### quitar contrato del gateway
        $respuesta[] = $this->removeContratoGateway();
        $this->activo = false;
        $this->baja = true;
        $this->save();
        $respuesta [] = $this->relEquipo->changeEquipoStatus(false);
        return $respuesta;
    }
    public function removeContratoGateway()
    {
        if ($apiMikro = $this->openSessionGateway($this)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($this->id, $clientsDataGateway);
            $apiMikro->removeClient($gatewayContract);
            while ($dhcp_id = $apiMikro->getIdDhcpServer($this->id)) {
                $apiMikro->delClientDhcp($dhcp_id);
            }
            $respuesta = 'EXITO: Contrato de ' . $this->relCliente->getNomYApe() . ' fue Removido con exito del Gateway!!';
        	unset($apiMikro);
        } else 
            {
                $respuesta = 'ERROR: No se pudo realizar el cambio.';
            }
        return($respuesta);
    }
    public function modifyContratoGateway($true_false = false )
    {
        if ($apiMikro = $this->openSessionGateway())
        {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($this->id, $clientsDataGateway);
            if ($gatewayContract->id_AddressList && $gatewayContract->id_HotspotUser)
            {
                $apiMikro->checkDhcpServer($this->relPlan->relPanel->relEquipo->ip);
                while ($dhcp_id = $apiMikro->getIdDhcpServer($this->id)) {
                    $apiMikro->delClientDhcp ($dhcp_id);
                }
                if ($this->tipo === 0 || $this->tipo === 1 || $this->tipo === 2) {
                    $apiMikro->addClientDhcp ([ 'address' => $this->relEquipo->ip,
                                                'mac-address' => $this->relEquipo->mac_address,
                                                'server' => 'SlamServer',
                                                'comment' => $this->id]);
                }
                if ($this->tipo === 2 || $this->tipo === 3) {
                    $apiMikro->addClientDhcp (['address' => $this->relRouter->ip,
                                    'mac-address' => $this->relRouter->mac_address,
                                    'server' => 'SlamServer',
                                    'comment' => $this->id]);
                }
                if ($this->tipo === 0 || $this->tipo === 1 ) {
                    $client_ip = $this->relEquipo->ip; 
                    $client_mac = $this->relEquipo->mac_address; 
                } else {
                    $client_ip = $this->relRouter->ip; 
                    $client_mac = $this->relRouter->mac_address; 
                }
                $apiMikro->setClient([
                    'id_HotspotUser' => $gatewayContract->id_HotspotUser,
                    'id_AddressList' => $gatewayContract->id_AddressList,
                    'name' => $client_ip, 
                    'mac-address' => $client_mac, 
                    'comment' => $this->id,
                    'server' => 'hotspot1',
                    'list' => $this->relPlan->id]);
                $respuesta = 'EXITO. Contrato de ' . $this->relCliente->getNomYApe() . ' modificado con Exito!!';
            }
            else
                {
                    $respuesta = $this->createContratoGateway();    
                }
            unset($apiMikro);
        } 
        else 
            {
                if ($true_false) {
                    return false;
                } else {
                    $respuesta = 'ERROR. al conectarse al Gateway: No se pudo modificar.';
                }
            }
        return ($respuesta);
    }
    public function renewIPAntenaClient()
    {
        if ($this->relEquipo->getUsuario() === null){
                $this->relEquipo->setUsPassInicial();
        }
        $ubiquiti = new Ubiquiti($this->relEquipo->ip, $this->relEquipo->getUsuario(), $this->relEquipo->getPassword(), false, 80, 5);
        if ($ubiquiti->setRenewDhcp()){
            return 'EXITO: IP antena cliente renovado OK.';
        }
        return 'ERROR: al renovar IP antena cliente.';
    }
    
}
