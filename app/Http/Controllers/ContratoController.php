<?php

namespace App\Http\Controllers;

use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use App\Custom\ubiquiti;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Equipo;
use App\Models\Panel;
use App\Models\Issue;
use App\Models\Plan;
use App\Models\Alta;
use App\Models\Contadores_mensuales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                $contrato = Contrato::where('num_cliente', $cliente->id)->get();
                foreach ($contrato as $item)
                    {
                        $contratos[] = $item;
                    }
            }
            $paginate = false;
        }
        elseif (isset($request['contrato'])){
            $contratos = Contrato::where('id', $request->input('contrato'))->get();
            $paginate = false;
        }else{
            $contratos = null;
            $paginate = false;
        }
        $conteos = Contadores_mensuales::get();
        return view('adminContratos', [ 'contratos' => isset($contratos) ? $contratos : [], 
                                        'internet' => 'active', 
                                        'paginate' => $paginate,
                                        'conteos' => $conteos,
                                        'website' => env('DOMINIO_COMFUEG'),
                                        'vuejs' => env('VUEJS_VERSION')]);
    }

    public function getContract ($id) {
        if (preg_match("/^[0-9]*$/", $id)) {
            $contrato = Contrato::find($id);
            $contrato->num_equipo = Equipo::find($contrato->num_equipo);
            unset($contrato->num_equipo->password);
            unset($contrato->num_equipo->usuario);
            $contrato->num_panel = Panel::find($contrato->num_panel);
            unset($contrato->num_panel->password);
            unset($contrato->num_panel->usuario);
            $contrato->num_cliente = Cliente::find($contrato->num_cliente);
            $contrato->num_plan = Plan::find($contrato->num_plan);
            $contrato->num_panel->id_equipo = Equipo::find($contrato->num_panel->id_equipo);
            unset($contrato->num_panel->id_equipo->password);
            unset($contrato->num_panel->id_equipo->usuario);
            if ($contrato) {
                return response()->json($contrato);
            }
        }
        return response()->json(null);
    }

    public function vueIndex ()
    {
        return view('altaContrato', [
            'contracts' => 'active',
            'website' => env('DOMINIO_COMFUEG'),
            'vuejs' => env('VUEJS_VERSION')
        ]);
    }
    
    public function getListadoContratosactivos ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $contratos = Contrato::where('no_paga', false)->where('baja', false)->get();
        $newFile = fopen ('../storage/app/public/ListadoClientes-' . date('Ymd') . '.csv', 'w');
        fwrite($newFile ,'ID Genesys;APELLIDO, Nombre;Plan;Estado;Sistema;Comentarios' . PHP_EOL);
        foreach ($contratos as $key => $value)
        {
            $pruebaVelocidad = Issue::where('titulo_id', 4)->where('contrato_id', $value->id)->get();
            fwrite($newFile ,   $value->relCliente->id . ';' . 
                                $value->relCliente->getNomyApe() . ';' . 
                                $value->relPlan->nombre . ';' . 
                                ($value->activo ? 'Habilitado' : 'Deshabilitado') .  
                                ';SLAM' . ';' .
                                ($pruebaVelocidad ? 'Con Prueba de Velocidad' : '') . 
                                PHP_EOL);
        }
        fclose($newFile);
        return Storage::disk('public')->download('ListadoClientes-' . date('Ymd') . '.csv');
    }
    public function getListadoContratosActivosFull ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $contratos = Contrato::where('no_paga', false)->where('baja', false)->get();
        $newFile = fopen ('../storage/app/public/ListadoClientes-ActivosFull-' . date('Ymd') . '.csv', 'w');
        fwrite($newFile ,'ID Contrato;Genesys ID;APELLIDO, Nombre;Plan;Estado;Barrio;Panel;Desde;Equipo;Reclamos' . PHP_EOL);
        foreach ($contratos as $key => $contrato)
        {
            fwrite($newFile ,   $contrato->id . ';' . 
                                $contrato->relCliente->id . ';' . 
                                $contrato->relCliente->getNomyApe() . ';' . 
                                $contrato->relPlan->nombre . ';' . 
                                ($contrato->activo ? 'Habilitado' : 'Deshabilitado') . ';' .
                                trim($contrato->relDireccion->relBarrio->nombre) . ';' . 
                                $contrato->relPanel->ssid . ';' . 
                                $contrato->created_at . ';' . 
                                $contrato->relEquipo->relProducto->modelo . ';' . 
                                (Issue::where('contrato_id', $contrato->id)->count()) . ';' . 
                                PHP_EOL
            );
        }
        fclose($newFile);
        return Storage::disk('public')->download('ListadoClientes-ActivosFull-' . date('Ymd') . '.csv');
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
    }

    public function getDataCreateEdit ($id_equipo = null)
    {
        $clientes = Cliente::orderBy('apellido')->get();
        $direcciones = Direccion::orderBy('numero')->get();
        $equipos = Equipo::where('ip', '0.0.0.0')
                            ->orwhere('ip', '>', '10.10.1.0')
                            ->where('fecha_baja', null)
                            ->orderBy('nombre')
                            ->get();
        foreach ($equipos as $key => $equipo)
        {
            if ( $id_equipo != $equipo->id && (Contrato::where('num_equipo', $equipo->id)->first()))
            {
                unset($equipos[$key]);
            }
        }
        $paneles = Panel::where('activo', true)->where('rol', 'PANEL')->orderBy('num_site')->get();
        $planes = Plan::where('gateway_id', '!=', null)->get();
        return ['internet' => 'active', 'clientes' => $clientes, 'direcciones' => $direcciones,
                    'equipos' => $equipos, 'paneles' => $paneles, 'planes' => $planes];
    }
    public function validarFromAlta(Request $request)
    {
        $condicion = [
            'alta_id' => 'required|numeric|min:1|max:99999',
            'num_equipo' => 'nullable|numeric|min:1|max:99999|unique:equipos,id',
            'num_panel' => 'nullable|numeric|min:1|max:99999',
            'router_id' => 'nullable|numeric|min:1|max:99999',
            'tipo' => 'required|numeric|min:1|max:5'
        ];
        $validator = Validator::make(
            $request->all(), $condicion);
            if ($validator->fails()) {
                return($validator->errors());
            }else {
                return false;
            }
    }
    public function storeContractFromAlta (Request $request){
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $alta = Alta::find($request->input('alta_id'));
        $contrato = new Contrato;
        if (!$mje = $this->validarFromAlta($request)) {
            $mensaje['error'][] = $mje;
            return response()->json($mensaje, 200);
        }
        $contrato->num_cliente = $alta->cliente_id;
        $contrato->num_plan = $alta->plan_id;
        $contrato->id_direccion = $alta->direccion_id;
        $contrato->num_equipo = ($request->input('num_equipo') ? $request->input('num_equipo') : null);
        $contrato->router_id = ($request->input('router_id') ? $request->input('router_id') : null);
        $contrato->num_panel = ($request->input('num_panel') ? $request->input('num_panel') : null);;
        $contrato->tipo = ($request->input('tipo'));
        $contrato->activo = false;
        $contrato->baja = false;
        $contrato->pem = false;
        $contrato->creator = auth()->user()->id;
        $contrato->save();
        $mensaje['success'][] = 'EXITO. Se grabó Contrato en Base de Datos.';
        switch ($contrato->tipo) {
            case 1:
                ##activa el equipo
                $contrato->relEquipo->activarEstado();
                ##set ip en num_equipo
                if ($contrato->relEquipo->setIpAuto()) {
                    ##Refresh al objeto
                    $contrato = $contrato->fresh();
                    $mensaje['success'][] = 'EXITO. Se asigno a la antena cliente el IP:' . $contrato->relEquipo->ip;
                    ##programar num_equipo en panel
                    if ($this->analizarRta($rta = $contrato->modificarMac(false))) {
                        $mensaje['success'][] = $rta;
                    } else {
                        $mensaje['error'][] = $rta;
                    }
                    ##programar num_equipo en mikrotik
                    if ($this->analizarRta($rta = $contrato->createContratoGateway())) {
                        $mensaje['success'][] = $rta;
                    } else {
                        $mensaje['error'][] = $rta;
                    }
                    ##bloquear num_equipo en mikrotik
                    if ($this->analizarRta($rta = $contrato->changeStateContratoGateway())) {
                        $mensaje['success'][] = $rta;
                    } else {
                        $mensaje['error'][] = $rta;
                    }
                } else {
                    $mensaje['error'][] = 'ERROR. NO asigno IP a la antena cliente con MacAddress:' . $contrato->relEquipo->macaddress;
                }
                break;
            
            case 2:
                dd('stop tipo 2');
                ##set ip en num_equipo
                $contrato->relEquipo->setIpAuto();
                ##set ip en router_id
                $contrato->relRouter->setIpAuto();
                ##Refresh al objeto
                $contrato = $contrato->fresh();
                ##programar router_id en mikrotic
                $contrato->createContratoGateway();
                ##programar num_antena en panel
                $contrato->modificarMac(false);
                ##bloquear router_id en mikrotik
                $contrato->changeStateContratoGateway();
                break;
            
            case 3:
                dd('stop tipo 3');
                ##set ip en router_id
                $contrato->relRouter->setIpAuto();
                ##Refresh al objeto
                $contrato = $contrato->fresh();
                ##programar router_id en mikrotik
                $contrato->createContratoGateway();
                ##bloquear num_equipo en mikrotik
                $contrato->changeStateContratoGateway();
                break;
            
            default:
                $mensaje['error'][] = 'ERROR. En el tipo de.';
                break;
        }
        /* 
        si tipo 1
            
        si tipo 2
            
        si tipo 3
            
         */
        $alta->programado = true;
        // $alta->instalacion_fecha debe ser la fecha que instaló luego de la prueba de instalacion.
        $alta->save();
        if (!isset($mensaje['error'])) {
            $mensaje['success'][] = 'EXITO. Contrato Cargado ok en panel y mikrotik.';
        }else {
            $mensaje['error'][] = 'ERROR. Se produjeron errores al cargar Contrato a panel o mikrotik.';
        }
        return response()->json($mensaje, 200);
    }

    private function analizarRta ($rta){
        if ($wordTest = str_split($rta, 5)[0] === 'EXITO') {
            return true;
        } elseif ($wordTest === 'ERROR') {
            return false;
        }
        return null;
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
        $contrato->baja = true;
        $contrato->save();
        ## activar equipo si hay error dar de baja el contrato.
        if ( $respuesta[] = $this->forzarAlta($equipo = Equipo::find($request['num_equipo'])) )
        {
            $equipo->save();
            $contrato->baja = false;
            $contrato->save();
            $contrato->relEquipo->refresh();
            $respuesta[] = $this->modificarMac($contrato, 0);
            $respuesta[] = $this->createContratoGateway($contrato);
        } else 
            {
                $equipo->fecha_baja = date('Y-m-d');
                $equipo->ip = '0.0.0.0';
                $equipo->save();
                $contrato->activo = false;
                $contrato->baja = true;
                $contrato->save();
                $respuesta[] = 'Error al asignar IP. No hay IPs libres en el segmento.';
            }
        return redirect('/adminContratos?contrato=' . $contrato->id)->with('mensaje', $respuesta);
    }

    public function validar(Request $request)
    {
        $aValidar = [
            'id' => 'nullable|numeric|min:1|max:99999',
            'num_cliente' => 'required|numeric|min:1|max:99999',
            'id_direccion' => 'required|numeric|min:1|max:99999',
            'num_equipo' => 'required|numeric|min:1|max:99999',
            'num_panel' => 'required|numeric|min:1|max:99999',
            'router_id' => 'nullable|numeric|min:1|max:99999',
            'num_plan' => 'required|numeric|min:1|max:99999'
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
        ##
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
        ### Borro mac de panel si cambio Equipo o Panel.
        if ( $contrato->relEquipo->id != Equipo::find($request['num_equipo'])->id ||
             $contrato->relPanel->id != Panel::find($request['num_panel'])->id
            )
            {
                $respuesta[] = $this->modificarMac($contrato, 1);
            }
        $contrato->num_equipo = $request['num_equipo'];
        ### Borro contrato si cambió Plan
        if ($contrato->relPlan->relPanel->id != Plan::find($request['num_plan'])->gateway_id)
            {
                $respuesta[] = $this->removeContratoGateway($contrato);
            }
        $contrato->num_panel = $request['num_panel'];
        $contrato->num_plan = $request['num_plan'];
        $contrato->created_at = $request['created_at'];
        $contrato->activo = (isset($request['activo']) && $request['activo'] == 'on') ? true : false;
        if ($contrato->relDireccion->coordenadas !== $request['coordenadas'] &&
            $contrato->id_direccion == $contrato->getOriginal()['id_direccion']
            )
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
            ## activar equipo nuevo
            if(!$respuesta[] = $this->changeEquipoStatus($contrato->num_equipo, true))
            {
                return redirect ('adminContratos?contrato=' . $request['id'])->with('mensaje', $respuesta);
            }
            ## desactivar equipo anterior
            $respuesta[] = $this->changeEquipoStatus($contrato->getOriginal()['num_equipo'], false);
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
        $contrato->save();
        unset($contrato);
        ##Si hubo cambio de panel o equipo aca se deberia setear el nuevo mac.
        $contrato = Contrato::find($request['id']);
        if (isset($momificar['equipo']) || isset($momificar['panel']))
        {
            $respuesta[] = $this->modificarMac($contrato, 0);
        }
        if (isset($momificar['equipo']) || isset($momificar['plan']))
        {
            $respuesta[] = $this->modifyContratoGateway($contrato);
            $respuesta[] = $this->renewIPAntenaClient($contrato);
        }
        if (isset($momificar['activo']))
        {
            $respuesta[] = $this->changeStateContratoGateway($contrato);
        }
        return redirect ('adminContratos?contrato=' . $request['id'])->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $contrato = Contrato::find($request['id']);
        $respuesta[] = $this->removeContratoGateway($contrato);
        $respuesta[] = $this->modificarMac($contrato, 1);
        $contrato->activo = false;
        $contrato->baja = true;
        $contrato->save();
        $respuesta [] = $this->changeEquipoStatus($contrato->relEquipo->id, false);
        $respuesta[] = "Se dió de BAJA el contrato N° $contrato->id";
        return redirect ('adminContratos?contrato=' . $request['id'])->with('mensaje', $respuesta);
    }

    private function changeEquipoStatus ($id_equipo, $darAlta)
    {
        $equipo = Equipo::find($id_equipo);
        if ($darAlta) 
        {
            if (!$rta = $this->forzarAlta($equipo)) {
                return false;
            }
        } else
                {
                    $equipo->fecha_baja = date('Y-m-d');
                    $equipo->ip = '0.0.0.0';
                    $rta = 'Equipo ' . $equipo->getResumida() . ' dado de baja';
                }
        $equipo->save();
        return $rta;
    }

    private function forzarAlta ($equipo)
    {
        $equipo->fecha_baja = null;
        ## Si viene con un IP verificar que no este usado
        ## sino set ip auto
        $rta = 'Equipo ' . $equipo->getResumida() . ' dado de alta.';
        if ( Equipo::ipLibrePaneles($equipo->ip, true) && Equipo::ipLibrePaneles($equipo->ip, false) )
        {
            if ($equipo->ip === '0.0.0.0') {
                $rta = $rta . 'Se asigna IP Auto.';
                return $equipo->setIpAuto() ? $rta : false;
            }
        }
        else {
            return false;
        }
        return $rta;
    }
    
    /**
     * ReAdd the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function undestroy(Request $request)
    {
        $contrato = Contrato::find($request['id']);
        if($respuesta[] = $this->changeEquipoStatus($contrato->relEquipo->id, true))
        {
            $contrato->relEquipo->refresh();
            $respuesta[] = $this->createContratoGateway($contrato);
            $respuesta[] = $this->modificarMac($contrato, 0);
            $contrato->activo = true;
            $contrato->baja = false;
            $contrato->save();
            $respuesta[] = "Se dió de ALTA nuevamente el contrato N° $contrato->id";
        } else {
            $respuesta[] = "ERROR al asignar IP auto en el ALTA del contrato N° $contrato->id";
        }
        return redirect ('adminContratos?contrato=' . $request['id'])->with('mensaje', $respuesta);
    }

    public function test ($id)
    {
        $contrato = Contrato::find($id);
        $conteo = Contadores_mensuales::where('contrato_id', $contrato->id)->first();
        return view ('testContrato', [
            'internet' => 'active', 
            'contrato' => $contrato,
            'conteo' => $conteo ?? new Contadores_mensuales,
            'website' => env('DOMINIO_COMFUEG'),
            'vuejs' => env('VUEJS_VERSION')]);
    }

    ################# Métodos de Gateway ####################################

    public function renewIPAntenaClient($contrato){
        if ($contrato->relEquipo->getUsuario() === null){
                $contrato->relEquipo->setUsPassInicial();
        }
        $ubiquiti = new Ubiquiti($contrato->relEquipo->ip, $contrato->relEquipo->getUsuario(), $contrato->relEquipo->getPassword(), false, 80, 5);
        if ($ubiquiti->setRenewDhcp()){
            return 'EXITO: IP antena cliente renovado OK.';
        }
        return 'ERROR: al renovar IP antena cliente.';
    }
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
            $respuesta = 'EXITO. Contrato de ' . $contrato->relCliente->getNomYApe() . ' creado con Exito en Gateway!!';
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
        if ($apiMikro = $this->openSessionGateway($contrato))
        {
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
            unset($apiMikro);
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
        if ($apiMikro = $this->openSessionGateway($contrato))
        {
            $clientsDataGateway = $apiMikro->getGatewayData();
            $gatewayContract = new ClientMikrotik($contrato->id, $clientsDataGateway, $contrato->relEquipo->mac_address);
            if ($contrato->activo)
            {
                $apiMikro->enableClient($gatewayContract);
                $respuesta = 'EXITO. Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue habilitado con Exito!!';
            }
            else
            {
                $apiMikro->disableClient($gatewayContract);
                $respuesta = 'EXITO. Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue deshabilitado con Exito!!';
            }
            unset($apiMikro);
        } else {
            $respuesta = 'ERROR. No se pudo realizar el cambio.';
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
            $respuesta = 'Contrato de ' . $contrato->relCliente->getNomYApe() . ' fue Removido con exito del Gateway!!';
        	unset($apiMikro);
        } else 
            {
                $respuesta = 'ERROR: No se pudo realizar el cambio.';
            }
        return($respuesta);
    }

    public function modificarMac (Contrato $contrato, $ope)
    {
        return ubiquiti::tratarMac(
            [
                'usuario' => $contrato->relPanel->relEquipo->getUsuario(),
                'password' => $contrato->relPanel->relEquipo->getPassword(),
                'ip' => $contrato->relPanel->relEquipo->ip,
                'contrato' => $contrato->id,
                'macaddress' => $contrato->relEquipo->mac_address,
                'ope' => $ope
            ]);
    }
}
