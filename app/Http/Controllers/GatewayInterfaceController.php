<?php

namespace App\Http\Controllers;

use App\Custom\GatewayMikrotik;
use App\Models\Panel;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class GatewayInterfaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gateways = Panel::where('rol', 'GATEWAY')->where('activo', true)->get();
        if (!$request['gateway_id'])
        {
            $elementos['rtas'] = [];
            $elementos['vlans'] = [];
            $gateway_id = null;
        }
        else 
            {
                $gateway_id = $request['gateway_id'];
                $gateway_target = Panel::find($gateway_id);
                $apiMikro = GatewayMikrotik::getConnection($gateway_target->relEquipo->ip, $gateway_target->relEquipo->getUsuario(), $gateway_target->relEquipo->getPassword());
                if ($apiMikro)
                {
                    $elementos = $apiMikro->getDatosinterfaces();
                    foreach ($elementos['vlans'] as $key => $value) {
                        if (Proveedor::tieneProveedor($value['.id']))
                        {
                            $elementos['vlans'][$key]['tieneVlan'] = true;
                        }
                        else 
                        {
                            $elementos['vlans'][$key]['tieneVlan'] = false;
                        }
                    }
                }
                else
                {
                    return 'No se pudo contactar Gateway';

                }
            }
        return view('adminInterfaces', ['gateways' => $gateways, 'providers' => 'active', 'elementos' => $elementos['rtas'], 'vlans' => $elementos['vlans'], 'gateway_id' => $gateway_id]);
        dd($elementos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($gateway_id)
    {
        $gateway_target = Panel::find($gateway_id);
        $apiMikro = GatewayMikrotik::getConnection($gateway_target->relEquipo->ip, $gateway_target->relEquipo->getUsuario(), $gateway_target->relEquipo->getPassword());
        if ($apiMikro) 
        {
            $interfaces = $apiMikro->getDatosInterfaces()['rtas'];
        } else {
            dd('error al conectarse al gateway');
        }
        unset($apiMikro);
        return view('agregarInterface', ['interfaces' => $interfaces, 'providers' => 'active', 'gateway_id' => $gateway_id]);
        dd($gateway_id);
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
        $gateway_id = $request['gateway_id'];
        $gateway_target = Panel::find($gateway_id);
        $apiMikro = GatewayMikrotik::getConnection($gateway_target->relEquipo->ip, $gateway_target->relEquipo->getUsuario(), $gateway_target->relEquipo->getPassword());
        if ($apiMikro) {
            $apiMikro->modifyVlan([
                        'name' => $request['name'],
                        'disabled' => $request['disabled'], 
                        'vlan-id' => $request['vlan-id'], 
                        'interface' => $request['interface'],
                        'comment' => 'addedBySlam'
                        ], 'add');
            if ($request['list'])
            {
                $apiMikro->modifyInterfaceListMember(['interface' => $request['name'], 'list' => $request['list'], 'comment' => 'addedBySlam'], 'add');           
            }
        } else {
            dd('error al conectarse al gateway');
        }
        unset($apiMikro);
        return redirect('adminInterfaces?gateway_id=' . $gateway_id);
        dd($request);
    }

    public function validar(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'list' => 'nullable',
                'disabled' => 'required',
                'vlan-id' => 'required|numeric',
                'interface' => 'required',
            ]
        );
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
        dd($id);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editInterface($interface_id, $gateway_id, $esVlan = false)
    {
        $gateway_target = Panel::find($gateway_id);
        $apiMikro = GatewayMikrotik::getConnection($gateway_target->relEquipo->ip, $gateway_target->relEquipo->getUsuario(), $gateway_target->relEquipo->getPassword());
        if ($apiMikro)
        {
            if (!$esVlan)
            {
                $elemento = $apiMikro->getDatosEthernet($interface_id);
                $interfaces = null;
            }
            else 
                {
                    $elemento = $apiMikro->getDatosEthernet($interface_id, true);
                    $interfaces = $apiMikro->getDatosInterfaces()['rtas'];
                }
        }
        else    
            {
                dd('error al conectarse al gateway');
            }
        //dd($elemento);
        return view('modificarInterface', ['interfaces' => $interfaces , 'elemento' => $elemento, 'providers' => 'active', 'gateway_id' =>$gateway_id, 'esVlan' => $esVlan]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateInterface(Request $request)
    {
        $gateway_id = $request['gateway_id'];
        $gateway_target = Panel::find($gateway_id);
        $apiMikro = GatewayMikrotik::getConnection($gateway_target->relEquipo->ip, $gateway_target->relEquipo->getUsuario(), $gateway_target->relEquipo->getPassword());
        if ($apiMikro)
        {
            $elemento = $apiMikro->getDatosEthernet($request['interface_id'], $request['esVlan'] ?? false);
            if ($request['name'] != $elemento['name'])
            {
                if (isset($request['esVlan'])) 
                {
                    $apiMikro->modifyVlan(['name' => $request['name'], 'numbers' => $elemento['.id'], 'comment' => 'modifiedBySlam'], 'set');
                }
                else
                    {
                        $apiMikro->modifyInterface(['name' => $request['name'], 'numbers' => $elemento['.id'], 'comment' => 'modifiedBySlam'], 'set');
                    }
            }
            if ($request['disabled'] != $elemento['disabled'])
            {
                if (isset($request['esVlan']))
                {
                    $apiMikro->modifyVlan(['disabled' => $request['disabled'], 'numbers' => $elemento['.id'], 'comment' => 'modifiedBySlam'], 'set');
                }
                else 
                    {
                        $apiMikro->modifyInterface(['disabled' => $request['disabled'], 'numbers' => $elemento['.id'], 'comment' => 'modifiedBySlam'], 'set');
                    }
            }
            if ( isset($elemento['list']) && $request['list'] != $elemento['list'] ){
                $numbers = $apiMikro->addListDataToInterface( $elemento, true);
                $apiMikro->modifyInterfaceListMember(['numbers' => $numbers, 'interface' => $request['name'], 'list' => $request['list'] , 'comment' => 'modifiedBySlam'], 'set');
            }
            if ( !isset($elemento['list']) && $request['list']){
                $apiMikro->modifyInterfaceListMember(['interface' => $request['name'], 'list' => $request['list'] , 'comment' => 'addedBySlam'], 'add');
            }
            if ( isset($elemento['list']) && !$request['list']){
                $numbers = $apiMikro->addListDataToInterface($elemento, true);
                $apiMikro->modifyInterfaceListMember(['numbers' => $numbers], 'remove');
            }
            if (isset($request['esVlan']))
            {
                if ($elemento['vlan-id'] != $request['vlan-id'])
                {
                    $apiMikro->modifyVlan(['numbers' => $elemento['.id'], 'vlan-id' => $request['vlan-id'], 'comment' => 'modifiedBySlam'], 'set');
                }
                if ($elemento['interface'] != $request['interface'])
                {
                    $apiMikro->modifyVlan(['numbers' => $elemento['.id'], 'interface' => $request['interface'], 'comment' => 'modifiedBySlam'], 'set');
                }
            }
        } else {
            dd('error al conectarse al gateway');
        }
        unset($apiMikro);
        return redirect('adminInterfaces?gateway_id=' . $gateway_id);
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($interface_id, $gateway_id)
    {
        $gateway_target = Panel::find($gateway_id);
        $apiMikro = GatewayMikrotik::getConnection($gateway_target->relEquipo->ip, $gateway_target->relEquipo->getUsuario(), $gateway_target->relEquipo->getPassword());
        if ($apiMikro) 
        {
            $elemento = $apiMikro->getDatosEthernet($interface_id, true);
            if (isset($elemento['list']))
            {
                $numbers = $apiMikro->addListDataToInterface($elemento, true);
                $apiMikro->modifyInterfaceListMember(['numbers' => $numbers], 'remove');
            }
            $apiMikro->modifyVlan(['numbers' => $elemento['.id']], 'remove');
            $respuesta[] = $elemento['name'] . ' eliminada correctamente';
        } 
        else 
            {
                $respuesta[] = 'error al conectarse al gateway';
            }
        unset($apiMikro);
        return redirect('adminInterfaces?gateway_id=' . $gateway_id)->with('mensaje', $respuesta);
    }
}
