<?php
namespace App\Custom;

class ClientMikrotik
{
	public $idGenesys;
	public $status = true;
	public $id_AddressList;
	public $Plan;
	public $disabled;
	public $address;
	public $id_HotspotUser;
	public $server;
	public $name_HotspotAddress;
	public $macAddress;
	public $bytesIn_Global;
	public $bytesOut_Global;
	public $id_HotspotHost;
	public $autorized;
	public $bytesIn_Partial;
	public $bytesOut_Partial;
	public $uptime;

	public __construct ($idGenesys, $addressList, $hotspotUser, $hotspotHost)
	{
		//bucar $idGenesysy en $addressList y copiar los datos
		//bucar $idGenesysy en $hotspotUser y copiar los datos
		//bucar $idGenesysy en $hotspotHost y copiar los datos
	}
}