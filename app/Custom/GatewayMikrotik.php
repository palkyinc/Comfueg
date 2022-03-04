<?php

namespace App\Custom;
use App\Custom\RouterosAPI;
use App\Models\Plan;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Config;

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

	#########		Clientes		#######################

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
	
	public function setClient ($datos) // $datos = [ id_HotspotUser => id_HotspotUser, id_AddressList => id_AddressList , $name => ip, $mac-address => mac, $comment => id_genesys, $server => servidor, $list => plan]
		{
			$this->comm("/ip/hotspot/user/set", array(
			    "numbers"     	=> $datos['id_HotspotUser'],
			    "name"     		=> $datos['mac-address'],
			    "mac-address"   => $datos['mac-address'],
			   	"comment"  		=> $datos['comment'],
			   	"server"		=> $datos['server']
			    ));
			
			$this->comm("/ip/firewall/address-list/set", array(
					    "numbers"     	=> $datos['id_AddressList'],
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
		if (isset($clienteMikrotik->id_HotspotHost)) {
			$this->comm("/ip/hotspot/host/remove", array(
				"numbers"     => $clienteMikrotik->id_HotspotHost
			));
		}
   	}

	public function resetCounter ()
	{
		$clienteMikrotik = $this->getGatewayData();
		foreach ($clienteMikrotik['hotspotHost'] as $value)
		{
			if (isset($value['comment']) && is_numeric($value['comment']))
			{
				$this->comm("/ip/hotspot/host/remove", array("numbers" => $value['.id']));
			}
		}
	}

	public function resetCounterMensual ()
	{
			$this->comm("/ip/hotspot/user/reset-counters");
	}

   	public function disableClient ($clienteMikrotik)
   	{
   		$this->comm("/ip/hotspot/user/disable", array (
   			"numbers" => $clienteMikrotik->id_HotspotUser,
   		));
   		$this->comm("/ip/firewall/address-list/disable", array (
   			"numbers" => $clienteMikrotik->id_AddressList,
   		));
		if (isset($clienteMikrotik->id_HotspotHost)) 
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
	   
	public function getGatewayData ($soloHotspotHost = false)
	{
		$this->write('/ip/hotspot/host/print');
		$hotspotHost = $this->parseResponse($this->read(false));

		if ($soloHotspotHost) {
			return $hotspotHost;
		}
		
		$this->write('/ip/hotspot/user/print');
		$hotspotUser = $this->parseResponse($this->read(false));
		
		$this->write('/ip/firewall/address-list/print');
		$addressList = $this->parseResponse($this->read(false));
		
		return ['hotspotHost' => $hotspotHost, 'hotspotUser' => $hotspotUser, 'addressList' => $addressList];
		
	}

	public function checkHotspotServer ($gateway_ip)
	{
		$this->write('/ip/hotspot/print');
		$hotspotServers = $this->parseResponse($this->read(false));
		foreach ($hotspotServers as $value)
		{
			if ($value['name'] == 'hotspot1' && $value['profile'] == 'hsprof1')
			{
				return true;
			}
		}
		$lanInterface = $this->getLanInterface();
		if ($lanInterface)
		{
			$this->comm('/ip/hotspot/profile/add',[	'name' => 'hsprof1',
													'hotspot-address' => $gateway_ip,
													'html-directory' => 'hotspot',
													'html-directory-override' => 'hotspot',
													'login-by' => 'mac,http-chap,https',
													'mac-auth-mode' => 'mac-as-username']);
			$this->comm('/ip/hotspot/add',[	'name' => 'hotspot1',
											'interface' => $lanInterface,
											'profile' => 'hsprof1',
											'disabled' => 'no']);
			return true;
		}
		return false;
	}

	public function checkDhcpServer ($gateway_ip)
	{
		$lanInterface = $this->getLanInterface();
		$this->write('/ip/dhcp-server/print');
		$dhcpServers = $this->parseResponse($this->read(false));
		foreach ($dhcpServers as $dhcpServer) {
			if ($dhcpServer['name'] == 'SlamServer' && $dhcpServer['interface'] == $lanInterface)
			{
				$this->write('/ip/dhcp-server/network/print');
				$networks = $this->parseResponse($this->read(false));
				foreach ($networks as $network) {
					if ($network['address'] == Config::get('constants.LAN_SEGMENT') && $network['gateway'] == $gateway_ip && $network['dns-server'] == $gateway_ip && $network['comment'] == 'addBySlam')
					{
						return true;
					}
					else
						{
							$this->comm('/ip/dhcp-server/network/remove', ['numbers' => $network['.id']]);
						}
				}
			}
			else
				{
					$this->comm('/ip/dhcp-server/network/remove', ['numbers' => $dhcpServer['.id']]);
				}
		}
		if($lanInterface)
		{
			$this->comm('/ip/dhcp-server/add', ['name' => 'SlamServer',
												'interface' => $lanInterface,
												'lease-time' => '23:59:59',
												'always-broadcast' => 'yes',
												'disabled' => 'no']);
			$this->comm('/ip/dhcp-server/network/add', ['address' => Config::get('constants.LAN_SEGMENT'),
														'gateway' => $gateway_ip,
														'comment' => 'addBySlam',
														'dns-server' => $gateway_ip]);
		}
		return false;
	}

	public function getIdDhcpServer($contrato_id)
	{
		$this->write('/ip/dhcp-server/lease/print');
		$dhcpId = $this->parseResponse($this->read(false));
		foreach ($dhcpId as $value) 
		{
			if (isset($value['comment']) && $value['comment'] == $contrato_id)
			{
				return ($value['.id']);
			}
		}
		return false;
	}

	public function getLanInterface()
	{
		$interfaces = $this->getDatosInterfaces();
		foreach ($interfaces as $tipo)
		{
			foreach ($tipo as $interface)
			{
				if (isset($interface['list']) && $interface['list'] == 'LAN' && $interface['disabled'] == 'false')
				{
					return $interface['name'];
				}
			}
		}
		return false;
	}
			
	#############		PLANES 		#####################################
	
	public function getPlanesData()
	{
		$this->write('/queue/type/print');
		$type = $this->parseResponse($this->read(false));

		$this->write('/queue/tree/print');
		$tree = $this->parseResponse($this->read(false));

		$this->write('/ip/firewall/mangle/print');
		$mangle = $this->parseResponse($this->read(false));

		return ['type' => $type, 'tree' => $tree, 'mangle' => $mangle];
	}
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
			"pcq-rate" => $up . 'K',
			"pcq-classifier" => "src-address"
		));
		$this->comm('/queue/type/add', array(
			'name'	=>	$nombre . "_down",
			'kind'	=>	'pcq',
			'pcq-rate' => $down . 'K',
			'pcq-classifier' => 'dst-address'
		));
	}

	public function crearPlanTree($nombre = 'total', $id = 'total')
	{
		$this->comm('/queue/tree/add', array(
			"name"	=> "DOWNLOAD_" . strtoupper($nombre),
			"packet-mark"	=> "DOWNLOAD_" . strtoupper($nombre),
			"parent"	=> $nombre == 'total' ? 'global' : 'DOWNLOAD_TOTAL',
			"queue"	=> $nombre . "_down",
			"comment"	=>  $id . "_down;Plan_id;addBySlam"
		));
		$this->comm('/queue/tree/add', array(
			"name"	=> "UPLOAD_" . strtoupper($nombre),
			"packet-mark"	=> "UPLOAD_" . strtoupper($nombre),
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

	###		Interfaces                ############################################################

	public function getDatosInterfaces()
	{
		$respuesta = null;
		$this->checkInterfacesList();
		$this->write('/interface/ethernet/print');
		$rtas = $this->parseResponse($this->read(false));
		$this->write('/interface/vlan/print');
		$vlans = $this->parseResponse($this->read(false));
		foreach ($rtas as $key => $value) {
			$status = $this->comm('/interface/ethernet/monitor', ['numbers' => $value['.id'], 'once' => null]);
			if ($status[0]['status'] == 'no-link')
			{
				$rtas[$key]['status'] = $status[0]['status'];
			}
			else
				{
					$rtas[$key]['status'] = $status[0]['status'];
					$rtas[$key]['rate'] = $status[0]['rate'] . ($status[0]['full-duplex'] == 'true' ? 'Full' : 'Half');
				}
			$this->addListDataToInterface($rtas[$key]);
		}
		foreach ($vlans as $key => $value) {
			$this->addListDataToInterface($vlans[$key]);
		}
		return(['rtas' => $rtas, 'vlans' => $vlans]);
	}

	public function getDatosEthernet($id, $esVlan = false)
	{
		if (!$esVlan)
		{
			$interface = $this->comm('/interface/ethernet/print', ['from' => $id]);
		}
		else 
			{
				$interface = $this->comm('/interface/vlan/print', ['from' => $id]);
			}
		$this->addListDataToInterface($interface[0]);
		return $interface[0];
	}

	public function addListDataToInterface (&$value, $retornarId = false)
	{
		$this->write('/interface/list/member/print');
		$lists = $this->parseResponse($this->read(false));
		//if (!isset($value['name'])) dd($value);
		foreach ($lists as $list) {
			if ($list['interface'] == $value['name']) {
				$value['list'] = $list['list'];
				if ($retornarId)
				{
					return $list['.id'];
				}
			}
		}
	}

	public function getDatosList()
	{
		$this->write('/interface/list/print');
		$rtas = $this->parseResponse($this->read(false));
		$respuesta = null;
		foreach ($rtas as $value) {
			if($value['name'] === 'WAN' || $value['name'] === 'LAN')
			{
				$respuesta [] = $value;
			}
		}
		return $respuesta;
	}

	public function checkInterfacesList()
	{
		$rtas = $this->getDatosList();
		if ($rtas)
		{
			foreach ($rtas as $key => $value) {
				if ($value['name'] == 'WAN') {$wanList = true;}
				if ($value['name'] == 'LAN') {$lanList = true;}
			}
		}
		if (!isset($wanlist))
		{
			$this->comm('/interface/list/add', ['name' => 'WAN', 'comment' => 'addBySlam']);
		}
		if (!isset($lanlist))
		{
			$this->comm('/interface/list/add', ['name' =>'LAN', 'comment' => 'addBySlam']);
		}
	}

	public function modifyInterface($array, $action) //numbers, name, disabled= yes | no
	{
		$this->comm('/interface/ethernet/' . $action, $array);
	}
	
	public function modifyVlan($array, $action) //$array = numbers, name, disabled= yes | no
	{
		$this->comm('/interface/vlan/' . $action, $array);
	}

	public function modifyInterfaceListMember($array, $action)
	{
		$this->comm('/interface/list/member/' . $action, $array);
	}

	####	NAT 		#################

	public function checkNat ()
	{
		$this->write('/ip/firewall/nat/print');
		$rtas = $this->parseResponse($this->read(false));
		foreach ($rtas as $value) {
			if ( isset($value['out-interface-list']) && $value['out-interface-list'] === 'WAN' && $value['chain'] === 'srcnat' && $value['action'] === 'masquerade') {
				return true;
			}
		}
		$this->comm('/ip/firewall/nat/add', ['chain' => 'srcnat',
											 'out-interface-list' => 'WAN',
											 'action' => 'masquerade']);
		return false;		
	}

	#########   PROVEEDORES 	######################

	public function modifyProveedor(Proveedor $proveedor, $action, $totalClassifiers, $cantClassifiers, $pointerClassifier)
	{
		if ($action == 'add')
		{
			$interface = $this->getDatosEthernet($proveedor->interface, $proveedor->esVlan)['name'];
			if ($proveedor->ipProveedor){
				$this->comm('/ip/address/add', ['address' => $proveedor->ipProveedor,
													'netmask' => $proveedor->maskProveedor,
													'interface' => $interface,
													'disabled' => 'no',
													'comment' => $proveedor->id . ';proveedor_id;A;addedBySlam']);
			}else {
				$this->comm('/ip/dhcp-client/add', ['add-default-route' => $proveedor->getProveedoresQuantity() == 1 ? 'yes' : 'no',
													'use-peer-dns' => 'no',
													'interface' => $interface,
													'disabled' => 'no',
													'comment' => $proveedor->id . ';proveedor_id;A;addedBySlam']);
			}
			//dd($proveedor->getProveedoresQuantity());
			$this->comm('/ip/firewall/mangle/' . $action, [	'chain' => 'prerouting',
															'in-interface' => $interface,
															'connection-mark' => 'no-mark',
															'action' => 'mark-connection',
															'new-connection-mark' => $interface . '_conn',
															'passthrough' => 'yes',
															'comment' => $proveedor->id . ';proveedor_id;A;addedBySlam' ]);
			for ($i=0; $i < $cantClassifiers; $i++)
			{ 
				$this->comm('/ip/firewall/mangle/' . $action, [	
															'chain' => 'prerouting',
															'src-address' => '10.10.0.0/16',
															'in-interface-list' => 'LAN',
															'connection-state' => 'established,related,new',
															'per-connection-classifier' => 'dst-address:' . $totalClassifiers . '/' . ($pointerClassifier + $i),
															'dst-address-type' => '!local',
															'action' => 'mark-connection',
															'new-connection-mark' => $interface . '_conn',
															'passthrough' => 'yes',
															'comment' => $proveedor->id . ';proveedor_id;B;addedBySlam']);
			}
			$this->comm('/ip/firewall/mangle/' . $action, [	'chain' => 'prerouting',
															'in-interface-list' => 'LAN',
															'connection-mark' => $interface . '_conn',
															'action' => 'mark-routing',
															'new-routing-mark' => 'to_' . $interface,
															'passthrough' => 'no' ,
															'comment' => $proveedor->id . ';proveedor_id;C;addedBySlam']);
			$this->comm('/ip/firewall/mangle/' . $action, [	'chain' => 'output',
															'connection-mark' => $interface . '_conn',
															'action' => 'mark-routing' ,
															'new-routing-mark' => 'to_' . $interface,
															'passthrough' => 'no',
															'comment' => $proveedor->id . ';proveedor_id;D;addedBySlam']);
				$this->comm('/ip/route/' . $action, [	'dst-address' => $proveedor->dns,
														'gateway' => $proveedor->ipGateway,
														'check-gateway' => 'ping',
														'distance' => 1,
														'scope' => 10,
														'comment' => $proveedor->id . ';proveedor_id;A;addedBySlam']);
				$this->comm('/ip/route/' . $action, [	'dst-address' => '0.0.0.0/0',
														'gateway' => $proveedor->dns,
														'check-gateway' => 'ping',
														'distance' => '1',
														'scope' => '30',
														'target-scope' => '10',
														'routing-mark' => 'to_' . $interface,
														'comment' => $proveedor->id . ';proveedor_id;B;addedBySlam']);
			if ($proveedor->getProveedoresQuantity() > 1)
			{
				$this->comm('/ip/route/' . $action, [	'dst-address' => '0.0.0.0/0',
														'gateway' => $proveedor->dns,
														'check-gateway' => 'ping',
														'distance' => 2,
														'scope' => 30,
														'target-scope' => 10,
														'routing-mark' => 'to_' . ($this->getDatosEthernet($proveedor->getNextInterface()->interface, $proveedor->getNextInterface()->esVlan)['name']),
														'comment' => $proveedor->id . ';proveedor_id;C;addedBySlam']);
			}
		}
		if ($action == 'remove')
		{
			$this->removeProveedor('/ip/route/print', '/ip/route/' . $action, $proveedor->id);
			$this->removeProveedor('/ip/firewall/mangle/print', '/ip/firewall/mangle/' . $action, $proveedor->id);
			$this->removeProveedor('/ip/dhcp-client/print', '/ip/dhcp-client/' . $action, $proveedor->id);
		}
	}

	private function removeProveedor($print, $remove, $proveedor_id, $all = false)
	{
		$this->write($print);
		$rtas = $this->parseResponse($this->read(false));
		foreach ($rtas as $key => $value) {
			if (isset($value['comment'])) {
				$comment = (explode(';', $value['comment']));
				if (isset($comment[1]) && $comment[1] == 'proveedor_id') 
				{
					if ($all)
					{
						$this->comm($remove, ['numbers' => $key]);
					}
					elseif ($comment[0] == $proveedor_id)
						{
							$this->comm($remove, ['numbers' => $key]);
						}
				}
			}
		}
	}

	public function removeAllProveedores()
	{
		$this->removeProveedor('/ip/route/print', '/ip/route/remove', 0, true);
		$this->removeProveedor('/ip/firewall/mangle/print', '/ip/firewall/mangle/remove', 0, true);
		$this->removeProveedor('/ip/dhcp-client/print', '/ip/dhcp-client/remove', 0, true);
	}
	
	public function setClock()
	{
		date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
		$date = date('M/d/Y');
		$time = date('H:i:s');
		//dd($time);
        $this->comm('/system/clock/set',
    				['date' => $date,
    				 'time' => $time]);
	}

	public function resetGateway()
	{
		$this->comm('/system/reboot');
	}

	public function makeBackup ()
	{
		$this->comm('/system/backup/save', ['dont-encrypt' => 'yes', 'name' => 'BKP_Diary']);
	}
}