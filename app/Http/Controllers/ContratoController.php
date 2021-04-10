<?php

namespace App\Http\Controllers;

use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Equipo;
use App\Models\Panel;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request['cliente']))
        {
            $apellido = strtoupper($request->input('cliente'));
            $clientes = Cliente::select("id")
                ->whereRaw("UPPER(apellido) LIKE (?)", ["%{$apellido}%"])
                ->get();
            foreach ($clientes as $key => $cliente) 
            {
                if ($contrato = Contrato::where('num_cliente', $cliente->id)->first())
                {
                    $contratos[] = $contrato;
                }
            }
            //dd($contratos);
            $paginate = false;
        }
        else
            {
                $contratos = Contrato::paginate(10);
                $paginate = true;
            }
        return view('adminContratos', ['contratos' => isset($contratos) ? $contratos : [], 'contracts' => 'active', 'paginate' => $paginate]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datos = $this->getDataCreateEdit();
        return view ('agregarContrato', $datos);
        dd($equipos);
    }

    public function getDataCreateEdit ($id_equipo = null)
    {
        $clientes = Cliente::orderBy('apellido')->get();
        $direcciones = Direccion::orderBy('numero')->get();
        $equipos = Equipo::where('ip', '>', '10.10.1.0')->where('fecha_baja', null)->orderBy('nombre')->get();
        foreach ($equipos as $key => $equipo)
        {
            if ( $id_equipo != $equipo->id && (Contrato::where('num_equipo', $equipo->id)->first()))
            {
                unset($equipos[$key]);
            }
        }
        $paneles = Panel::where('activo', true)->where('rol', 'PANEL')->orderBy('num_site')->get();
        $planes = Plan::where('gateway_id', '!=', null)->get();
        return ['contracts' => 'active', 'clientes' => $clientes, 'direcciones' => $direcciones,
                    'equipos' => $equipos, 'paneles' => $paneles, 'planes' => $planes];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validar($request);
        $contrato = new Contrato();
        $contrato->num_cliente = $request['num_cliente'];
        $contrato->id_direccion = $request['id_direccion'];
        $contrato->num_panel = $request['num_panel'];
        $contrato->num_plan = $request['num_plan'];
        $contrato->num_equipo = $request['num_equipo'];
        $contrato->activo = (isset($request['activo']) && $request['activo'] == 'on') ? true : false;
        $contrato->save();
        $respuesta[] = $this->createContratoGateway($contrato);
        $respuesta[] = 'Contrato se creo correctamente';
        return redirect('/adminContratos')->with('mensaje', $respuesta);
    }

    public function validar(Request $request)
    {
        $aValidar = [
            'id' => 'nullable|numeric|min:1|max:99999',
            'num_cliente' => 'required|min:1|max:99999',
            'id_direccion' => 'required|min:1|max:99999',
            'num_equipo' => 'required|min:1|max:99999',
            'num_panel' => 'nullable|min:1|max:99999',
            'num_plan' => 'required|min:1|max:99999'
        ];
        if (isset($request['created_at']))
        {
            $aValidar['created_at'] = 'required|date';
        }
        if (isset($request['activo']))
        {
            $aValidar['activo'] = 'required';
        }
        if (isset($request['coordenadas']))
        {
            $aValidar['coordenadas'] = 'nullable|min:22|max:40';
        }
        $request->validate($aValidar);
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
        $elemento = Contrato::find($id);
        $datos = $this->getDataCreateEdit($elemento->num_equipo);
        $datos['elemento'] =  $elemento;
        return view('modificarContrato', $datos);
        dd($elemento);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validar($request);
        $contrato = Contrato::find($request['id']);
        $contrato->num_cliente = $request['num_cliente'];
        $contrato->id_direccion = $request['id_direccion'];
        $contrato->num_equipo = $request['num_equipo'];
        $contrato->num_panel = $request['num_panel'];
        $contrato->num_plan = $request['num_plan'];
        $contrato->created_at = $request['created_at'];
        $contrato->activo = (isset($request['activo']) && $request['activo'] == 'on') ? true : false;
        if ($contrato->relDireccion->coordenadas !== $request['coordenadas'])
        {
            $direccion = Direccion::find($contrato->id_direccion);
            $direccion->coordenadas = $request['coordenadas'];
            $direccion->save();
        }
        $respuesta[] = 'Se cambió con exito:';
        if ($contrato->num_cliente != $contrato->getOriginal()['num_cliente']) {
            $respuesta[] = ' Cliente: ' . $contrato->getOriginal()['num_cliente'] . ' POR ' . $contrato->num_cliente;
        }
        if ($contrato->id_direccion != $contrato->getOriginal()['id_direccion']) {
            $respuesta[] = ' Dirección: ' . $contrato->getOriginal()['id_direccion'] . ' POR ' . $contrato->id_direccion;
        }
        if ($contrato->num_equipo != $contrato->getOriginal()['num_equipo']) {
            $respuesta[] = ' Equipo: ' . $contrato->getOriginal()['num_equipo'] . ' POR ' . $contrato->num_equipo;
            $momificar['equipo'] = true;
        }
        if ($contrato->num_panel != $contrato->getOriginal()['num_panel']) {
            $respuesta[] = ' Panel: ' . $contrato->getOriginal()['num_panel'] . ' POR ' . $contrato->num_panel;
            $momificar['panel'] = true;
        }
        if ($contrato->num_plan != $contrato->getOriginal()['num_plan']) {
            $respuesta[] = ' Plan: ' . $contrato->getOriginal()['num_plan'] . ' POR ' . $contrato->num_plan;
            $momificar['plan'] = true;
        }
        if ($contrato->created_at != $contrato->getOriginal()['created_at']) {
            $respuesta[] = ' Creado el: ' . $contrato->getOriginal()['created_at'] . ' POR ' . $contrato->created_at;
        }
        if ($contrato->activo != $contrato->getOriginal()['activo']) {
            $respuesta[] = ' Habilitado: ' . $contrato->getOriginal()['activo'] . ' POR ' . $contrato->activo;
            $momificar['activo'] = true;
        }
        if (isset($momificar['equipo']) || isset($momificar['plan']))
        {
            $respuesta[] = $this->modifyContratoGateway($contrato);
        }
        if (isset($momificar['activo']))
        {
            $respuesta[] = $this->changeStateContratoGateway($contrato);
        }
        $contrato->save();
        return redirect ('adminContratos')->with('mensaje', $respuesta);
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

    ################# Métodos de Gateway ####################################

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
    public function createContratoGateway(Contrato $contrato)
    {
        if ($apiMikro = $this->openSessionGateway($contrato)) 
        {
            $apiMikro->addClient([
                'name' => $contrato->relEquipo->ip,
                'mac-address' => $contrato->relEquipo->mac_address,
                'comment' => $contrato->id, ';contrato_id;A;addedBySlam',
                'server' => 'hotspot1',
                'list' => $contrato->relPlan->id
            ]);
            $apiMikro->checkDhcpServer($contrato->relPlan->relPanel->relEquipo->ip);
            $apiMikro->comm('/ip/dhcp-server/lease/add', [  'address' => $contrato->relEquipo->ip,
                                                            'mac-address' => $contrato->relEquipo->mac_address,
                                                            'server' => 'SlamServer',
                                                            'comment' => $contrato->id]);
            $respuesta = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' creado con Exito en Gateway!!';
        } 
        else 
            {
                $respuesta = 'ERROR al conectarse al Gateway: No se pudo crear.';
            }
        return($respuesta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function modifyContratoGateway($contrato)
    {
        if ($apiMikro = $this->openSessionGateway($contrato)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway);
            if ($gatewayContract->id_AddressList && $gatewayContract->id_HotspotUser)
            {
                $apiMikro->setClient([
                        'id_HotspotUser' => $gatewayContract->id_HotspotUser,
                        'id_AddressList' => $gatewayContract->id_AddressList,
                        'name' => $contrato->relEquipo->ip,
                        'mac-address' => $contrato->relEquipo->mac_address,
                        'comment' => $contrato->id,
                        'server' => 'hotspot1',
                        'list' => $contrato->relPlan->id]);
                        $apiMikro->checkDhcpServer($contrato->relPlan->relPanel->relEquipo->ip);
                        $apiMikro->comm('/ip/dhcp-server/lease/set', [  'numbers' => $apiMikro->getIdDhcpServer($contrato->id),
                                                                        'address' => $contrato->relEquipo->ip,
                                                                        'mac-address' => $contrato->relEquipo->mac_address]);
                        $respuesta = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' modificado con Exito!!';
            }
            else
                {
                    $respuesta = $this->createContratoGateway($contrato);
                }
        } 
        else 
            {
                $respuesta = 'ERROR al conectarse al Gateway: No se pudo modificar.';
            }
        return ($respuesta);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStateContratoGateway($contrato)
    {
        if ($apiMikro = $this->openSessionGateway($contrato)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway);
            if ($contrato->activo)
            {
                $apiMikro->enableClient($gatewayContract);
                $respuesta = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            }
            else
            {
                $apiMikro->disableClient($gatewayContract);
                $respuesta = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue deshabilitado con Exito!!';
            }
        } else {
            $respuesta = 'ERROR: No se pudo realizar el cambio.';
        }
        return($respuesta);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeContratoGateway($contrato)
    {
        if ($apiMikro = $this->openSessionGateway($contrato)) {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway);
            $apiMikro->removeClient($gatewayContract);
            $apiMikro->comm('/ip/dhcp-server/lease/remove', ['numbers' => $apiMikro->getIdDhcpServer($contrato->id)]);
            $respuesta = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            
        } else 
            {
                $respuesta = 'ERROR: No se pudo realizar el cambio.';
            }
        return($respuesta);
    }
}
