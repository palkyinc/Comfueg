<?php

namespace App\Custom;
//require('routeros_api.class.php');

class GatewayMikrotik extends RouterosAPI
{
	private $id_session;
	static $mikrotik;

	public static function getConnection($ip, $usuario, $contrasenia)
	{
		if (isset(self::$mikrotik))
		{
			// itero $mikrotik
			foreach (self::$mikrotik as $gateway) {
				if ($gateway->id_session == $ip)
				{
					// si el $ip == $this->$id_sesion, devuelvo la sesion
					return $gateway;
				}
			}
		}
		//llamo a getApiPointer()
		// returno la sesion
		return self::getApiPointer($ip, $usuario, $contrasenia);
		
	}

	private static function getApiPointer($ip, $usuario, $contrasenia)
	{
		//Creo la sesion, $this->$id_session y la sumo a 4mikrotik
		$api = new GatewayMikrotik();
		$api->id_session = $ip;
		if ($api->connect($ip, $usuario, $contrasenia))
		{
			return self::$mikrotik[] = $api;
		}
		return false;
	}

	public function addClient ($datos) // $datos = [ $name => ip, $mac-address => mac, $comment => id_genesys, $server => servidor, $list => plan]
	{
		$this->comm("/ip/hotspot/user/add", array(
		    "name"     		=> $datos['mac-address'],
		    "mac-address"   => $datos['mac-address'],
		   	"comment"  		=> $datos['comment'],
		   	"server"		=> $datos['server']
		    ));
		
		$this->comm("/ip/firewall/address-list/add", array(
				    "list"     		=> $datos['list'],
				    "address"   => $datos['name'],
				   	"comment"  		=> $datos['comment']
				   	));
   	}
	
	public function setClient ($datos) // $datos = [ $idContrato => contrato, $name => ip, $mac-address => mac, $comment => id_genesys, $server => servidor, $list => plan]
		{
			$this->comm("/ip/hotspot/user/set", array(
			    "numbers"     	=> $datos['idContrato'],
			    "name"     		=> $datos['mac-address'],
			    "mac-address"   => $datos['mac-address'],
			   	"comment"  		=> $datos['comment'],
			   	"server"		=> $datos['server']
			    ));
			
			$this->comm("/ip/firewall/address-list/set", array(
					    "numbers"     	=> $datos['idContrato'],
			    		"list"     		=> $datos['list'],
					    "address"   	=> $datos['name'],
					   	"comment"  		=> $datos['comment']
					   	));
	   	}

   	public function removeClient ($clienteMikrotik)
   	{
   		$this->comm("/ip/hotspot/user/remove", array (
   			"numbers" => $clienteMikrotik->id_HotspotUser,
   		));
   		$this->comm("/ip/firewall/address-list/remove", array (
   			"numbers" => $clienteMikrotik->id_AddressList,
   		));
   	}

   	public function disableClient ($clienteMikrotik)
   	{
   		$this->comm("/ip/hotspot/user/disable", array (
   			"numbers" => $clienteMikrotik->id_HotspotUser,
   		));
   		$this->comm("/ip/firewall/address-list/disable", array (
   			"numbers" => $clienteMikrotik->id_AddressList,
   		));
   		if ($clienteMikrotik->id_HotspotHost) 
		{
			$this->comm("/ip/hotspot/host/remove", array(
					      "numbers"     => $clienteMikrotik->id_HotspotHost
					   ));
		}
   	}
	
	public function enableClient ($clienteMikrotik)
	   	{
	   		$this->comm("/ip/hotspot/user/enable", array (
	   			"numbers" => $clienteMikrotik->id_HotspotUser,
	   		));
	   		$this->comm("/ip/firewall/address-list/enable", array (
	   			"numbers" => $clienteMikrotik->id_AddressList,
	   		));
	   		if ($clienteMikrotik->id_HotspotHost) 
			{
				$this->comm("/ip/hotspot/host/remove", array(
						     "numbers"     => $clienteMikrotik->id_HotspotHost
						   ));
			}
	   	}

   	public function getGatewayData ()
   	{
   		$this->write('/ip/hotspot/host/print');
		$hotspotHost = $this->parseResponse($this->read(false));

		$this->write('/ip/hotspot/user/print');
		$hotspotUser = $this->parseResponse($this->read(false));

		$this->write('/ip/firewall/address-list/print');
		$addressList = $this->parseResponse($this->read(false));

		return ['hotspotHost' => $hotspotHost, 'hotspotUser' => $hotspotUser, 'addressList' => $addressList];
		   
   	}

}
