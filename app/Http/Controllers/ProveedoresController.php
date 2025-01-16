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
    public function index(Request $request)
    {
        $paginate = false;
        $actualizar = false;
        $gateways = Panel::where('rol', 'GATEWAY')->where('activo', true)->get();
        if (!$gateway_id = isset($request['gateway_id']) ? $request['gateway_id'] : null)
        {
            $proveedores = [];
        }
        else 
        {
            $proveedores = Proveedor::where('gateway_id', $gateway_id)->get();
            foreach ($proveedores as $proveedor) {
                if ($proveedor->sinActualizar)
                {
                    $actualizar = true;
                }
            }
        }
        return view('adminProveedores',['paginate' => $paginate, 
                                        'providers' => 'active', 
                                        'proveedores' => $proveedores, 
                                        'gateways' => $gateways,
                                        'gateway_id' => $gateway_id,    
                                        'actualizar' => $actualizar]);
    }

    /**
     * Vista para la selección de Gateway.
     *
     * @return \Illuminate\Http\Response
     */
    public function preCreate()
    {
        $gateways = Panel::where('rol', 'GATEWAY')->get();
        return view ('agregarProveedorGateway', ['providers' => 'active', 'gateways' => $gateways]);
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
            $interfaces[] = $apiMikro->getDatosInterfaces(); ### Las No usadas
            foreach ($interfaces[0]['rtas'] as $key => $value) {
                if (Proveedor::where('interface', $value['.id'])->where('gateway_id', $gateway->id)->first() ||
                    !isset($value['list']) || $value['list'] != 'WAN')
                {
                    unset($interfaces[0]['rtas'][$key]);
                }
            }
            foreach ($interfaces[0]['vlans'] as $key => $value) {
                if (Proveedor::where('interface', $value['.id'])->where('gateway_id', $gateway->id)->first() ||
                    !isset($value['list']) || $value['list'] != 'WAN')
                {
                    unset($interfaces[0]['vlans'][$key]);
                }
            }
        }
        return view ('agregarProveedor',[   'interfaces' => $interfaces[0],
                                            'providers' => 'active',
                                            'gateway' => $gateway]);
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
                'ipProveedor' => 'nullable|ipv4',
                'maskProveedor' => 'nullable|ipv4',
                'div_classifier' => 'required|min:1|max:99999',
                'gateway_id' => 'required|min:0|max:99999',
                'wan_failover_id' => 'required|min:0|max:100'
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
        $proveedor->ipProveedor = $request->input('ipProveedor');
        $proveedor->maskProveedor = $request->input('maskProveedor');
        $proveedor->wan_failover_id = $request->input('wan_failover_id');
        $proveedor->sinActualizar = true;
        $proveedor->en_linea = false;
        $proveedor->contaOffline = 4;
        $this->setDivClassifier($request->gateway_id, $request->div_classifier);
        $proveedor->save();
        $respuesta['success'][] = 'Proveedor se creo correctamente';
        return redirect('/adminProveedores?gateway_id=' . $request->gateway_id)->with('mensaje', $respuesta);
       
    }

    private function setDivClassifier($gateway_id, $div_classifier)
    {
        $gateway = Panel::find($gateway_id);
        if (!$gateway->wan_failover && $div_classifier != $gateway->div_classifier) {
            # cambiar div_classifier y grabar
            $gateway->div_classifier = $div_classifier;
            $gateway->save();
            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        ###
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
            $interfaces[] = $apiMikro->getDatosInterfaces(); ### Las No usadas
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
        return view('modificarProveedor', [ 'interfaces' => $interfaces[0], 
                                            'proveedor' => $proveedor,
                                            'providers' => 'active']);
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
        $proveedor->estado = $request['estado']; ### si cambio de estado hay que revisar los classifier
        $interface = explode('?', $request->input('interface'));
        $proveedor->interface = ((isset($interface[1]) && $interface[1] == 'v') ? $interface[0] : $request->input('interface'));
        $proveedor->esVlan = ((isset($interface[1]) && $interface[1] == 'v') ? true : false);
        $proveedor->bajada = $request['bajada'];
        $proveedor->subida = $request['subida'];
        $proveedor->dns = $request['dns'];
        $proveedor->ipGateway = $request['ipGateway'];
        $proveedor->ipProveedor = $request['ipProveedor'];
        $proveedor->maskProveedor = $request['maskProveedor'];
        $proveedor->sinActualizar = false;
        $proveedor->wan_failover_id = $request['wan_failover_id'];
        if ($this->setDivClassifier($request->gateway_id, $request->div_classifier)) {
            $respuesta['info'][] = 'Divisor Classifier: ' . $proveedor->relGateway->div_classifier . ' POR ' . $request->div_classifier;
        }
        if ($proveedor->wan_failover_id != $proveedor->getOriginal()['wan_failover_id']) {
            $respuesta['info'][] = ' Wan Failover Id: ' . $proveedor->getOriginal()['wan_failover_id'] . ' POR ' . $proveedor->wan_failover_id;
        }
        if ($proveedor->nombre != $proveedor->getOriginal()['nombre']) {
            $respuesta['info'][] = ' Nombre: ' . $proveedor->getOriginal()['nombre'] . ' POR ' . $proveedor->nombre;
        }
        if ($proveedor->estado != $proveedor->getOriginal()['estado']) {
            $respuesta['info'][] = ' Estado: ' . $proveedor->getOriginal()['estado'] . ' POR ' . $proveedor->estado;
        }
        if ($proveedor->interface != $proveedor->getOriginal()['interface']) {
            $respuesta['info'][] = ' Interface: ' . $proveedor->getOriginal()['interface'] . ' POR ' . $proveedor->interface;
        }
        if ($proveedor->bajada != $proveedor->getOriginal()['bajada']) {
            $respuesta['info'][] = ' Bajada: ' . $proveedor->getOriginal()['bajada'] . ' POR ' . $proveedor->bajada;
        }
        if ($proveedor->subida != $proveedor->getOriginal()['subida']) {
            $respuesta['info'][] = ' Subida: ' . $proveedor->getOriginal()['subida'] . ' POR ' . $proveedor->subida;
        }
        if ($proveedor->dns != $proveedor->getOriginal()['dns']) {
            $respuesta['info'][] = ' DNS Recursividad: ' . $proveedor->getOriginal()['dns'] . ' POR ' . $proveedor->dns;
        }
        if ($proveedor->ipGateway != $proveedor->getOriginal()['ipGateway']) {
            $respuesta['info'][] = ' IP del Default Gateway: ' . $proveedor->getOriginal()['ipGateway'] . ' POR ' . $proveedor->ipGateway;
        }
        if ($proveedor->ipProveedor != $proveedor->getOriginal()['ipProveedor']) {
            $respuesta['info'][] = ' IP Proveedor: ' . $proveedor->getOriginal()['ipProveedor'] . ' POR ' . $proveedor->ipProveedor;
        }
        if ($proveedor->maskProveedor != $proveedor->getOriginal()['maskProveedor']) {
            $respuesta['info'][] = ' Mask Proveedor: ' . $proveedor->getOriginal()['maskProveedor'] . ' POR ' . $proveedor->maskProveedor;
        }
        if (!isset($respuesta))
        {
            $respuesta['info'][] = 'No se registran cambios.';
        } else {
            $proveedor->sinActualizar = true;
        }
        $proveedor->save();
        return redirect('adminProveedores?gateway_id=' . $proveedor->gateway_id)->with('mensaje', $respuesta);
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
        $gateway_id = $proveedor->relGateway->id;
        $respuesta['success'][] = 'Se eliminó Proveedor: ' . $proveedor->nombre;
        $proveedor->delete();
        return redirect('adminProveedores?gateway_id=' . $gateway_id)->with('mensaje', $respuesta);
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
            $respuesta = [];
            $proveedoresActualizar = Proveedor::where('gateway_id', $sinActualizar->gateway_id)
                                                        ->where('estado', true)
                                                        ->get();
            $gateway = Panel::find($sinActualizar->gateway_id);
            $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
            $numbers = $apiMikro->getTypeNumbers();
            ### Verifica que los proveedores a Actualizar tengan wna failover correcto
            foreach ($proveedoresActualizar as $key => $value) {
                if ($value->wan_failover_id > 0)
                {
                    $wan_failover[$value->id] = $value->wan_failover_id;
                } else {
                    $wan_failover_balanced = true;
                }
            }
            ### Si mal configurado wan failovewr vuelve a adminProveedores
            if (isset($wan_failover) && isset($wan_failover_balanced))
            {
                $respuesta['error'][] = 'ID Wan Failover mal configurado.';
                unset($apiMikro);
                        return redirect('adminProveedores?gateway_id=' . (isset($gateway->id) ? $gateway->id : ''))->with('mensaje', ($respuesta));
            ### Si esta seteado failover debe ordenar los proveedores y configurarlo en Mikrotik
            }
            else if(isset($wan_failover))
            {
                asort($wan_failover);
                foreach ($wan_failover as $key => $value) {
                    if (!isset($count)){
                        $count = 1;
                    } else {
                        $count++;
                    }
                    ### Si los ID no estám em orden debe volver a adminProveedores
                    if ($wan_failover[$key] !== $count)
                    {
                        $respuesta['error'][] = 'ID Wan Failover mal configurado.';
                        unset($apiMikro);
                        return redirect('adminProveedores?gateway_id=' . (isset($gateway->id) ? $gateway->id : ''))->with('mensaje', ($respuesta));
                    }
                    
                    foreach ($proveedoresActualizar as $key => $proveedor) {
                        if ($wan_failover[$proveedor->id] === 1) {
                            $totales['bajada'] = $proveedor->bajada;
                            $totales['subida'] = $proveedor->subida;
                        }
                    }
                }
                if ($apiMikro) 
                {
                    $respuesta += $this->modPlanTypes($numbers, $apiMikro, $totales);
                    foreach ($proveedoresActualizar as $key => $proveedor) {
                        $apiMikro->removeAddressProveedor($proveedor->id);
                        $apiMikro->modifyProveedor($proveedor, 'add', null, null, null, true);
                        $proveedor->sinActualizar = false;
                        $proveedor->save();
                    }
                }
                $respuesta += $this->proveedoresActualizarUnable ($sinActualizar);
            }
            else if(isset($wan_failover_balanced))
            {
                $sinActualizar->reordenarClassifiers();
                $totales = $sinActualizar->reordenarTotales();
                $totalClassifiers = $sinActualizar->getClassifiersQuantity();
                if ($apiMikro) 
                {
                    $respuesta += $this->modPlanTypes($numbers, $apiMikro, $totales);
                    
                    $pointerClassifier = 0;
                    foreach ($proveedoresActualizar as $proveedor)
                    {
                        $cantClassifiers = round($proveedor->bajada/$proveedor->relGateway->div_classifier);
                        $apiMikro->removeAddressProveedor($proveedor->id);
                        $apiMikro->modifyProveedor($proveedor, 'add', $totalClassifiers, $cantClassifiers, $pointerClassifier);
                        $proveedor->sinActualizar = false;
                        $proveedor->save();
                        $pointerClassifier += $cantClassifiers;
                    }
                    $respuesta += $this->proveedoresActualizarUnable ($sinActualizar);
                }
                else
                {
                    $respuesta['error'][] = 'Error al intentar conectarse a: ' . $gateway->relEquipo->nombre;
                }
            } else {
                ($sinActualizar->relGateway);
                $sinActualizar->reordenarClassifiers(true);
            }
        }
        if (!isset($respuesta)) {$respuesta['info'][] = 'Nada para actualizar';}
        unset($apiMikro);
        return redirect('adminProveedores?gateway_id=' . (isset($gateway->id) ? $gateway->id : ''))->with('mensaje', ($respuesta));
    }
    private function proveedoresActualizarUnable ($sinActualizar) {

        $proveedoresActualizarUnable = Proveedor::where('gateway_id', $sinActualizar->gateway_id)
                        ->where('estado', false)
                        ->get();
        foreach ($proveedoresActualizarUnable as $proveedor) {
            $proveedor->sinActualizar = false;
            $proveedor->save();
        }
        $respuesta['success'][] = 'Proveedores de Gateway Actualizado.';
        return $respuesta;
    }
    private function modPlanTypes($numbers, $apiMikro, $totales)
    {
        $respuesta = [];
        if (($numbers) == null){
            $respuesta += $apiMikro->modificarPlanType(   ['name' => 'total_down', 'kind' => 'pcq', 'pcq-classifier' => 'dst-address'], 
                                        ['name' => 'total_up', 'kind' => 'pcq', 'pcq-classifier' => 'src-address'], 'add');
            $numbers = $apiMikro->getTypeNumbers();
        }
        $respuesta += $apiMikro->modificarPlanType(   ['numbers' => $numbers['down'], 'pcq-rate' => $totales['bajada'] . 'K'], 
                                        ['numbers' => $numbers['up'], 'pcq-rate' => $totales['subida'] . 'K'], 'set');
        $apiMikro->removeAllProveedores();
        $respuesta ['success'][] = $apiMikro->checkNat() ? 'Se confirma Regla de NAT se encuentra creada.' : 'Se creó regla de NAT.';
        return $respuesta;
    }
}
