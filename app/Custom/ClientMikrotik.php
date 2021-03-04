<?php
namespace App\Custom;

class ClientMikrotik
{
	public $idContrato;
	public $status = true;
	public $id_AddressList;
	public $Plan;
	public $addressDisabled;
	public $address;
	public $id_HotspotUser;
	public $server;
	public $name_HotspotAddress;
	public $macAddress;
	public $bytesIn_Global;
	public $bytesOut_Global;
	public $hotspotDisabled;
	public $id_HotspotHost;
	public $autorized;
	public $bytesIn_Partial;
	public $bytesOut_Partial;
	public $uptime;

	public function __construct ($contrato = null, $dataArray = null) // dataArray ['addressList', 'hotspotUser', 'hotspotHost']
	{
		if ($contrato)
		{
			//bucar $contratoy en $dataArray['addressList'] y copiar los datos
			$this->idContrato = $contrato;
			foreach ($dataArray['addressList'] as $key => $value) {
				if ($value['comment'] == $contrato)
				{
					$this->setAddressListData ($key, $value);
				}
			}
			//bucar $idContratoy en $dataArray['hotspotUser'] y copiar los datos
			foreach ($dataArray['hotspotUser'] as $key => $value) {
				if ($value['comment'] == $contrato)
				{
					$this->setHotspotUserData($key, $value);
				}

			}
			//bucar $idContratoy en $dataArray['hotspotHost'] y copiar los datos
			foreach ($dataArray['hotspotHost'] as $key => $value) {
				if (isset($value['comment']) && $value['comment'] == $contrato)
				{
					$this->setHotspotHostData($key, $value);
				}
			}
		}//else
	}

	public function setAddressListData ($key, $value)
	{
		$this->id_AddressList = $key;
		$this->Plan = $value['list'];
		$this->addressDisabled = $value['disabled'];
		$this->address = $value['address'];
	}

	public function setHotspotHostData ($key, $value)
	{
			$this->id_HotspotHost = $key;
			$this->autorized = $value['authorized'];
			$this->bytesIn_Partial = $value['bytes-in'];
			$this->bytesOut_Partial = $value['bytes-out'];
			$this->uptime = $value['uptime'];
	}
	
	public function setHotspotUserData ($key, $value)
		{
			$this->id_HotspotUser = $key;
			$this->server = $value['server'] ?? null;
			$this->name_HotspotAddress = $value['name'];
			$this->macAddress = $value['mac-address'] ?? null;
			$this->bytesIn_Global = $value['bytes-in'];
			$this->bytesOut_Global = $value['bytes-out'];
			$this->hotspotDisabled = $value['disabled'];
		}

	public function getUnauthorized($key, $dataArray, $API) // dataArray = $hotspotHost, $addressList, $hotspotUser, 
	{
		$this->status = false;
		$this->setHotspotHostData($key, $dataArray['hotspotHost'][$key]);
		foreach ($dataArray['hotspotUser'] as $id_HotspotUser => $value) {
			if (isset($value['mac-address']) && $dataArray['hotspotHost'][$key]['mac-address'] === $value['mac-address'])
			{
				if ($this->findClientByMacAddress($value['comment'], $dataArray['addressList'], $dataArray['hotspotHost'][$key]['address']) && 
					$this->checkBoolean($value['disabled']))
				{
					$this->setHotspotUserData($id_HotspotUser, $value);
					$this->idContrato = $value['comment'];
					$this->status = true;
				} else
					{
						echo 'borraando Key'. $key . 'de hotspotHost... <br> ';
						$this->deleteClientFromHost($key, $API);
   					}
			}
		}
		if (!$this->status)
			{
				return false;
			}
		return true;
	}

	private function checkBoolean($disabledStatus)
	{
		if ($disabledStatus === 'true')
		{
			return true;
		} else
			{
				return false;
			}
	}

	private function findClientByMacAddress ($idContrato, $addressList, $hotspotHostAddress)
	{
		foreach ($addressList as $id_AddressList => $value) {
				if ($hotspotHostAddress == $value['address'] && $idContrato == $value['comment'])
				{
					$this->setAddressListData($id_AddressList, $value);
					return true;
				}
			}
		return false;
	}

	private function deleteClientFromHost ($key, $API)
	{
		if ($API) 
		{
			$API->comm("/ip/hotspot/host/remove", array(
					      "numbers"     => $key
					   ));
		}

	}
}