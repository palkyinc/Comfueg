<?php

namespace App\Http\Controllers;

use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use App\Models\Contrato;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //...
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    ################# Métodos de Gateway

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function openSessionGateway($contrato)
    {
        $apiMikro = GatewayMikrotik::getConnection($contrato->relPlan->relPanel->relEquipo->ip, $contrato->relPlan->relPanel->relEquipo->getUsuario(), $contrato->relPlan->relPanel->relEquipo->getPassword());
        if ($apiMikro) 
        {
            if ($apiMikro->checkHotspotServer($contrato->relPlan->relPanel->relEquipo->ip)) 
            {
                return $apiMikro;
            }
        }
        return false;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createContratoGateway($id)
    {
        $contrato = Contrato::find($id);
        if ($apiMikro = $this->openSessionGateway($contrato)) 
        {
            $apiMikro->addClient([
                'name' => $contrato->relEquipo->ip,
                'mac-address' => $contrato->relEquipo->mac_address,
                'comment' => $contrato->id, ';contrati_id;A;addedBySlam',
                'server' => 'hotspot1',
                'list' => $contrato->relPlan->id
            ]);
            $apiMikro->checkDhcpServer($contrato->relPlan->relPanel->relEquipo->ip);
            $apiMikro->comm('/ip/dhcp-server/lease/add', [  'address' => $contrato->relEquipo->ip,
                                                            'mac-address' => $contrato->relEquipo->mac_address,
                                                            'server' => 'SlamServer',
                                                            'comment' => $contrato->id]);
            $respuesta[] = 'Contrato de ' . $contrato->relCliente->getNomYApe() . 'creado con Exito!!';
        } else 
        {
            $respuesta[] = 'ERROR: No se pudo crear.';
        }
        dd($respuesta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function modifyContratoGateway($id)
    {
        $contrato = Contrato::find($id);
        if ($apiMikro = $this->openSessionGateway($contrato)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway);
            $apiMikro->setClient([
                    'id_HotspotUser' => $gatewayContract->id_HotspotUser,
                    'id_AddressList' => $gatewayContract->id_AddressList,
                    'name' => $contrato->relEquipo->ip,
                    'mac-address' => $contrato->relEquipo->mac_address,
                    'comment' => $contrato->id,
                    'server' => 'hotspot1',
                    'list' => $contrato->relPlan->id
            ]);
            $apiMikro->comm('/ip/dhcp-server/lease/set', [  'number' => $apiMikro->getIdDhcpServer($contrato->id),
                                                            'address' => $contrato->relEquipo->ip,
                                                            'mac-address' => $contrato->relEquipo->mac_address]);
            $respuesta[] = 'Contrato de ' . $contrato->relCliente->getNomYApe() . 'modificado con Exito!!';
        } else {
            $respuesta[] = 'ERROR: No se pudo modificar.';
        }
        dd($respuesta);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStateContratoGateway($id)
    {
        $contrato = Contrato::find($id);
        if ($apiMikro = $this->openSessionGateway($contrato)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway);
            if ($contrato->activo)
            {
                $apiMikro->enableClient($gatewayContract);
                $respuesta[] = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            }
            else
            {
                $apiMikro->disableClient($gatewayContract);
                $respuesta[] = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue deshabilitado con Exito!!';
            }
        } else {
            $respuesta[] = 'ERROR: No se pudo realizar el cambio.';
        }
        dd($respuesta);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeContratoGateway($id = 1)
    {
        $contrato = Contrato::find($id);
        if ($apiMikro = $this->openSessionGateway($contrato)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway);
            $apiMikro->removeClient($gatewayContract);
            $apiMikro->comm('/ip/dhcp-server/lease/remove', ['numbers' => $apiMikro->getIdDhcpServer($contrato->id)]);
            $respuesta[] = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            
        } else 
            {
                $respuesta[] = 'ERROR: No se pudo realizar el cambio.';
            }
        dd($respuesta);
    }
}
