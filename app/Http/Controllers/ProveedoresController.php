<?php

namespace App\Http\Controllers;

use App\Custom\GatewayMikrotik;
use App\Models\Panel;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($gateway_id = null)
    {
        if (!$gateway_id)
        {
            $proveedores = Proveedor::paginate(10);
        }
        else 
        {
            $proveedores = Proveedor::where('gateway_id', $gateway_id)->paginate(10);
        }
        $actualizar = false;
        foreach ($proveedores as $proveedor) {
            if ($proveedor->sinActualizar)
            {
                $actualizar = true;
            }
        }
        $gateways = Panel::where('rol', 'GATEWAY')->get();
        return view('adminProveedores', ['datos' => 'active', 'proveedores' => $proveedores, 'gateways' => $gateways, 'actualizar' => $actualizar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function preCreate()
    {
        $gateways = Panel::where('rol', 'GATEWAY')->get();
        return view ('agregarProveedorGateway', ['datos' => 'active', 'gateways' => $gateways]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $gateway = Panel::find($request->input('gateway_id'));
        $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
        if ($apiMikro) 
        {
            $interfaces[] = $apiMikro->getDatosInterfaces(); //Las No usadas
            foreach ($interfaces[0]['rtas'] as $key => $value) {
                if (Proveedor::where('interface', $value['.id'])->first() ||
                    !isset($value['list']) || $value['list'] != 'WAN')
                {
                    unset($interfaces[0]['rtas'][$key]);
                }
            }
            foreach ($interfaces[0]['vlans'] as $key => $value) {
                if (Proveedor::where('interface', $value['.id'])->first() ||
                    !isset($value['list']) || $value['list'] != 'WAN')
                {
                    unset($interfaces[0]['vlans'][$key]);
                }
            }
        }
        return view ('agregarProveedor', ['interfaces' => $interfaces[0],'datos' => 'active', 'gateway' => $gateway]);
    }

    public function validar(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required|min:2|max:30',
                'interface' => 'required|min:1|max:6',
                'estado' => 'required|boolean',
                'bajada' => 'required|min:1|max:99999',
                'subida' => 'required|min:1|max:99999',
                'dns' => 'ipv4',
                'ipGateway' => 'ipv4',
                'gateway_id' => 'required|min:0|max:99999'
            ]
        );
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
        $proveedor = new Proveedor();
        $proveedor->nombre = $request->input('nombre');
        $proveedor->estado = $request->input('estado') == 1 ? true : false;
        $interface = explode('?', $request->input('interface'));
        $proveedor->interface = (( isset($interface[1]) && $interface[1] == 'v') ? $interface[0] : $request->input('interface'));
        $proveedor->esVlan = ((isset($interface[1]) && $interface[1] == 'v') ? true : false);
        $proveedor->bajada = $request->input('bajada');
        $proveedor->subida = $request->input('subida');
        $proveedor->classifier = $proveedor->getProveedoresQuantity();
        $proveedor->dns = $request->input('dns');
        $proveedor->gateway_id = $request->input('gateway_id');
        $proveedor->ipGateway = $request->input('ipGateway');
        $proveedor->sinActualizar = true;
        $proveedor->save();
        $respuesta[] = 'Proveedor se creo correctamente';
        return redirect('/adminProveedores')->with('mensaje', $respuesta);
       
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
        $proveedor = Proveedor::find($id);
        $apiMikro = GatewayMikrotik::getConnection($proveedor->relGateway->relEquipo->ip, $proveedor->relGateway->relEquipo->getUsuario(), $proveedor->relGateway->relEquipo->getPassword());
        if ($apiMikro) {
            $interfaces[] = $apiMikro->getDatosInterfaces(); //Las No usadas
            foreach ($interfaces[0]['rtas'] as $key => $value) {
                $candidato = Proveedor::where('interface', $value['.id'])->first();
                if (
                    (isset($candidato) && $candidato->interface != $proveedor->interface) ||
                    !isset($value['list']) || $value['list'] != 'WAN'
                ) {
                    unset($interfaces[0]['rtas'][$key]);
                }
            }
            foreach ($interfaces[0]['vlans'] as $key => $value) {
                $candidato = Proveedor::where('interface', $value['.id'])->first();
                if (
                    (isset($candidato) && $candidato->interface != $proveedor->interface) ||
                    !isset($value['list']) || $value['list'] != 'WAN'
                ) {
                    unset($interfaces[0]['vlans'][$key]);
                }
            }
        }
        return view('modificarProveedor', ['interfaces' => $interfaces[0], 'proveedor' => $proveedor, 'datos' => 'active']);
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
        $proveedor = Proveedor::find($request['id']);
        $proveedor->nombre = $request['nombre'];
        $proveedor->estado = $request['estado']; // si cambio de estado hay que revisar los classifier
        $interface = explode('?', $request->input('interface'));
        $proveedor->interface = ((isset($interface[1]) && $interface[1] == 'v') ? $interface[0] : $request->input('interface'));
        $proveedor->esVlan = ((isset($interface[1]) && $interface[1] == 'v') ? true : false);
        $proveedor->bajada = $request['bajada'];
        $proveedor->subida = $request['subida'];
        $proveedor->dns = $request['dns'];
        $proveedor->ipGateway = $request['ipGateway'];
        $proveedor->sinActualizar = true;
        if ($proveedor->nombre != $proveedor->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $proveedor->getOriginal()['nombre'] . ' POR ' . $proveedor->nombre;
        }
        if ($proveedor->estado != $proveedor->getOriginal()['estado']) {
            $respuesta[] = ' Estado: ' . $proveedor->getOriginal()['estado'] . ' POR ' . $proveedor->estado;
        }
        if ($proveedor->interface != $proveedor->getOriginal()['interface']) {
            $respuesta[] = ' Interface: ' . $proveedor->getOriginal()['interface'] . ' POR ' . $proveedor->interface;
        }
        if ($proveedor->bajada != $proveedor->getOriginal()['bajada']) {
            $respuesta[] = ' Bajada: ' . $proveedor->getOriginal()['bajada'] . ' POR ' . $proveedor->bajada;
        }
        if ($proveedor->subida != $proveedor->getOriginal()['subida']) {
            $respuesta[] = ' Subida: ' . $proveedor->getOriginal()['subida'] . ' POR ' . $proveedor->subida;
        }
        if ($proveedor->dns != $proveedor->getOriginal()['dns']) {
            $respuesta[] = ' DNS Recursividad: ' . $proveedor->getOriginal()['dns'] . ' POR ' . $proveedor->dns;
        }
        if ($proveedor->ipGateway != $proveedor->getOriginal()['ipGateway']) {
            $respuesta[] = ' IP del Default Gateway: ' . $proveedor->getOriginal()['ipGateway'] . ' POR ' . $proveedor->ipGateway;
        }
        $proveedor->save();
        return redirect('adminProveedores')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);
        $respuesta[] = 'Se eliminó Proveedor: ' . $proveedor->nombre;
        $proveedor->delete();
        return redirect('adminProveedores')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  null
     * @return \Illuminate\Http\Response
     */
    public function refreshGateway ()
    {
        while ($sinActualizar = Proveedor::where('sinActualizar', true)->first()) 
        {
            $sinActualizar->reordenarClassifiers();
            $totales = $sinActualizar->reordenarTotales();
            $totalClassifiers = $sinActualizar->getClassifiersQuantity();
            $gateway = Panel::find($sinActualizar->gateway_id);
            $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
            if ($apiMikro) 
            {
                $numbers = $apiMikro->getTypeNumbers();
                $apiMikro->modificarPlanType(   ['numbers' => $numbers['down'], 'pcq-rate' => $totales['bajada'] . 'K'], 
                                                ['numbers' => $numbers['up'], 'pcq-rate' => $totales['subida'] . 'K'], 'set');
                $respuesta[] = ($apiMikro->checkNat() ? 'Regla de NAT confirmada.' : 'Se creó regla de NAT.');
                $apiMikro->removeAllProveedores();
                $proveedoresActualizar = Proveedor::where('gateway_id', $sinActualizar->gateway_id)
                                                    ->where('estado', true)
                                                    ->get();
                $pointerClassifier = 0;
                foreach ($proveedoresActualizar as $proveedor)
                {
                    $cantClassifiers = round($proveedor->bajada/5120);
                    //dd($cantClassifiers);
			        $apiMikro->modifyProveedor($proveedor, 'add', $totalClassifiers, $cantClassifiers, $pointerClassifier);
                    $proveedor->sinActualizar = false;
                    $proveedor->save();
                    $pointerClassifier += $cantClassifiers;
                }
                $respuesta[] = 'Gateways Actualizados!!';
                unset($apiMikro);
            }
            else
            {
                $respuesta [] = 'Error al instentar conectarse a ' . $gateway->relEquipo->nombre;
            }
        }
        return redirect('adminProveedores')->with('mensaje', $respuesta);
    }
}
