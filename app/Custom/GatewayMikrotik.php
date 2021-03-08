<?php

namespace App\Custom;
use App\Custom\RouterosAPI;
use App\Models\Plan;

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
	
	   //funciones TYPE Tree mangel

	public function checkTotales ()
	{
		$totalType = $this->getTypeNumbers();
		if (!isset($totalType['down']) || !isset($totalType['up'])) {
			$this->crearPlanType();
		}
		
		$totalTree = $this->getTreeNumbers();
		if (!isset($totalTree['down']) || !isset($totalTree['up'])) {
			$this->crearPlanTree();
		}
	}

	public function getTreeNumbers($id = 'total')
	{
		$this->write('/queue/tree/print');
		$rtas = $this->parseResponse($this->read(false));
		$respuesta = null;
		foreach ($rtas as $key => $rta) {
			if (isset($rta['comment']) && explode(';',$rta['comment'])[0] == $id . '_down') {
				$respuesta['down'] = $key;
			}
			if (isset($rta['comment']) && explode(';', $rta['comment'])[0] == $id . '_up') {
				$respuesta['up'] = $key;
			}
		}
		return $respuesta;
		
	}
	
	public function getMangleNumbers($id)
	{
		$this->write('/ip/firewall/mangle/print');
		$rtas = $this->parseResponse($this->read(false));
		$respuesta = null;
		foreach ($rtas as $key => $rta) {
			if (isset($rta['comment']) && explode(';',$rta['comment'])[0] == $id . '_down') {
				$respuesta['down'] = $key;
			}
			if (isset($rta['comment']) && explode(';', $rta['comment'])[0] == $id . '_up') {
				$respuesta['up'] = $key;
			}
		}
		return $respuesta;
		
	}

	public function getTypeNumbers($nombre = 'total')
	{
		$respuesta = null;
		$this->write('/queue/type/print');
		$rtas = $this->parseResponse($this->read(false));
		foreach ($rtas as $key => $rta) {
			if (isset($rta['name']) && $rta['name'] == $nombre . '_down') 
			{
				$respuesta['down'] = $key;
			}
			if (isset($rta['name']) && $rta['name'] == $nombre . '_up') {
				$respuesta['up'] = $key;
			}
		}
		return $respuesta;
	}
	
	public function crearPlanType($nombre = 'total', $up = '768K', $down = '1024K')
	{
		$this->comm('/queue/type/add', array(
			"name"	=>	$nombre . "_up",
			"kind"	=>	"pcq",
			"pcq-rate" => $up,
			"pcq-classifier" => "src-address"
		));
		$this->comm('/queue/type/add', array(
			'name'	=>	$nombre . "_down",
			'kind'	=>	'pcq',
			'pcq-rate' => $down,
			'pcq-classifier' => 'dst-address'
		));
	}
	public function crearPlanTree($nombre = 'total', $id = 'total')
	{
		$this->comm('/queue/tree/add', array(
			"name"	=> "DOWNLOAD_" . strtoupper($nombre),
			"parent"	=> $nombre == 'total' ? 'global' : 'DOWNLOAD_TOTAL',
			"queue"	=> $nombre . "_down",
			"comment"	=>  $id . "_down;Plan_id;addBySlam"
		));
		$this->comm('/queue/tree/add', array(
			"name"	=> "UPLOAD_" . strtoupper($nombre),
			"parent"	=> ($nombre == 'total' ? 'global' : 'UPLOAD_TOTAL'),
			"queue"	=> $nombre . "_up",
			"comment"	=>  $id . "_up;Plan_id;addBySlam",
		));
	}
	
	public function crearPlanMangle($nombre, $id)
	{
		$this->comm('/ip/firewall/mangle/add', array(
			'chain' => 'forward',
			'src-address-list' => $nombre,
			'action' => 'mark-packet',
			'new-packet-mark' => "UPLOAD_" . strtoupper($nombre),
			'passthrough' => 'no',
			"comment"	=>  $id . "_down;Plan_id;addBySlam"
		));
		$this->comm('/ip/firewall/mangle/add', array(
			'chain' => 'forward',
			'dst-address-list' => $nombre,
			'action' => 'mark-packet',
			'new-packet-mark' => "DOWNLOAD_" . strtoupper($nombre),
			'passthrough' => 'no',
			"comment"	=>  $id . "_up;Plan_id;addBySlam",
		));
	}

	
	public function modificarPlanType($down, $up, $action)
	{
		if ($down !== null){
			$this->comm('/queue/type/'.$action, $down);
		}
		if ($up !== null){
			$this->comm('/queue/type/'.$action, $up);
		}
	}
	
	public function modificarPlanTree($down, $up, $action)
	{
		if ($down !== null){
			$this->comm('/queue/tree/'.$action, $down);
		}
		if ($up !== null){
			$this->comm('/queue/tree/'.$action, $up);
		}
	}
	
	public function modificarPlanMangle($down, $up, $action)
	{
		if ($down !== null){
			$this->comm('/ip/firewall/mangle/'.$action, $down);
		}
		if ($up !== null){
			$this->comm('/ip/firewall/mangle/'.$action, $up);
		}
	}

}
