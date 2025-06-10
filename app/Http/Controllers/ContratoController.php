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
use Illuminate\Support\Facades\Auth;

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
    public function getContract ($id)
    {
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
        fwrite($newFile ,'ID Genesys;APELLIDO, Nombre;Plan;Estado;Sistema;Panel;Nodo;Barrio;Comentarios' . PHP_EOL);
        foreach ($contratos as $key => $value)
        {
            $pruebaVelocidad = Issue::where('titulo_id', 4)->where('contrato_id', $value->id)->where('closed', false)->first();
            fwrite($newFile ,   $value->relCliente->id . ';' . 
                                $value->relCliente->getNomyApe() . ';' . 
                                $value->relPlan->nombre . ';' . 
                                ($value->activo ? 'Habilitado' : 'Deshabilitado') .  
                                ';SLAM' . ';' .
                                $value->relPanel->ssid . ';' .
                                $value->relPanel->relSite->nombre . ';' .
                                $value->relDireccion->relBarrio->nombre . ';' .
                                ($pruebaVelocidad ? 'Con Prueba de Velocidad' : '') .
                                PHP_EOL);
        }
        fclose($newFile);
        return Storage::disk('public')->download('ListadoClientes-' . date('Ymd') . '.csv');
    }
    public function getListadoContratosNoActivos ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $contratos = Contrato::where('no_paga', false)->where('baja', true)->get();
        $newFile = fopen ('../storage/app/public/ListadoContratosNoActivos-' . date('Ymd') . '.csv', 'w');
        fwrite($newFile ,'ID Genesys;APELLIDO, Nombre;Plan;Estado;Sistema;Panel;Nodo;Barrio;Comentarios' . PHP_EOL);
        foreach ($contratos as $key => $value)
        {
            $pruebaVelocidad = Issue::where('titulo_id', 4)->where('contrato_id', $value->id)->get();
            fwrite($newFile ,   $value->relCliente->id . ';' . 
                                $value->relCliente->getNomyApe() . ';' . 
                                $value->relPlan->nombre . ';' . 
                                ($value->activo ? 'Habilitado' : 'Deshabilitado') .  
                                ';SLAM' . ';' .
                                $value->relPanel->ssid . ';' .
                                $value->relPanel->relSite->nombre . ';' .
                                $value->relDireccion->relBarrio->nombre . ';' .
                                ($pruebaVelocidad ? 'Con Prueba de Velocidad' : '') .
                                PHP_EOL);
        }
        fclose($newFile);
        return Storage::disk('public')->download('ListadoContratosNoActivos-' . date('Ymd') . '.csv');
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
    public function storeContractFromAlta (Request $request)
    {
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
        $mensaje['success'][] = 'EXITO. Se guardo Contrato.';
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
        $alta->contrato_id = $contrato->id;
        ## $alta->instalacion_fecha debe ser la fecha que instaló luego de la prueba de instalacion.
        $alta->save();
        if (!isset($mensaje['error'])) {
            $mensaje['success'][] = 'EXITO. Contrato Cargado ok en panel y mikrotik.';
        }else {
            $mensaje['error'][] = 'ERROR. Se produjeron errores al cargar Contrato a panel o mikrotik.';
        }
        return response()->json($mensaje, 200);
    }
    private function analizarRta ($rta)
    {
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
        $contrato = Contrato::find($id);
        $datos = $this->getDataCreateEdit($contrato->num_equipo);
        $datos['tipos'] = [0 =>'Default (Standard)', 1 => 'STANDARD', 2 => 'ANTENA BRIDGE + ROUTER CLIENTE', 3 => 'SOLO ROUTER CLIENTE'];
        $datos['issue_id'] = ($issue_id = Issue::where('contrato_id', $contrato->id)->where('titulo_id', 11)->where('closed', false)->first()) ? $issue_id->id : NULL;
        $datos['elemento'] =  $contrato;
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
        /* if ($contrato->activo != $contrato->getOriginal()['activo']) {
            $respuesta[] = ' Habilitado: ' . $contrato->getOriginal()['activo'] . ' POR ' . $contrato->activo;
            $momificar['activo'] = true;
        } */
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
        /* if (isset($momificar['activo']))
        {
            $respuesta[] = $this->changeStateContratoGateway($contrato);
        } */
        return redirect ('adminContratos?contrato=' . $request['id'])->with('mensaje', $respuesta);
    }
    public function updateTipo (Request $request)
    {
        $contrato = Contrato::find($request['id']);
        $contrato->tipo = $request['tipo'];
        $respuesta['success'][] = $descripcion = 'Cambio Tipo en contrato N°: ' . $contrato->id . ' de: ' . $contrato->relCliente->getNomYApe() . ', se cambia tipo de contrato de tipo: ' . $contrato->getOriginal()['tipo'] . ' a: ' . $contrato->tipo . '.';
        $contrato->save();
        $ticket = \App\Models\Issue::create([
                    'titulo_id' => 12,
                    'descripcion' => $descripcion,
                    'asignado_id' => Auth::id(),
                    'creator_id' => 1,
                    'cliente_id' => $contrato->relCliente->id,
                    'contrato_id' => $contrato->id,
                    'closed' => true]);
        $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en titular del contrato N°: ' . $contrato->id;
        return redirect ('modificarContrato/' . $request['id'])->with('mensaje', $respuesta);
    }
    public function updateNoPaga ($id){
        $contrato = Contrato::find($id);
        $anterior = $contrato->no_paga;
        $contrato->no_paga = $contrato->no_paga ? 0 : 1;
        $posterior = $contrato->no_paga;
        $respuesta['success'][] = $descripcion = 'Cambio en contrato N°: ' . $contrato->id . ' de: ' . $contrato->relCliente->getNomYApe() . ', se cambia Status Comercial de ' . ($contrato->no_paga ? 'Factura -> No Factura' : 'No Factura -> Factura') . '.';
        $ticket = \App\Models\Issue::create([
                    'titulo_id' => 9,
                    'descripcion' => $descripcion,
                    'asignado_id' => Auth::id(),
                    'creator_id' => 1,
                    'cliente_id' => $contrato->relCliente->id,
                    'contrato_id' => $contrato->id,
                    'closed' => true]);
        $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en titular del contrato N°: ' . $contrato->id;
        $contrato->save();
        return redirect('/inicio')->with('mensaje', $respuesta);
    }
    public function updateEquipo (Request $request)
    {
        $hoy = date('d.M.Y');
        $contrato = Contrato::find($request['id']);
        if (isset($request->mac_address) && ($contrato->tipo === 0 || $contrato->tipo === 1 || $contrato->tipo === 2) ) {
            $new_equipo = Equipo::where('mac_address', strtoupper($request->mac_address))->first();
            if (!$new_equipo) {
                $respuesta['error'][] = 'No se encuentra equipo con Mac Address: ' . $request->mac_address . ' cargado en la base de datos.';
                return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
            }
        } else {
            $new_equipo = null;
        }
        if (isset($request->mac_address_2) && ($contrato->tipo === 2 || $contrato->tipo === 3 ) ) {
            $new_router = Equipo::where('mac_address', strtoupper($request->mac_address_2))->first();
            if (!$new_router) {
                $respuesta['error'][] = 'No se encuentra equipo con Mac Address: ' . $request->mac_address_2 . ' cargado en la base de datos.';
                return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
            }
        } else {
            $new_router = null;
        }
        if ($new_equipo && $new_router && $new_equipo->id === $new_router->id) {
            $respuesta['error'][] = 'Se deben ingresan Mac Address distintos';
                return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }
        $old_equipo = $contrato->relEquipo;
        $old_router = $contrato->relRouter;
        $old_contrato_equipo = ($new_equipo) ? Contrato::where('num_equipo', $new_equipo->id)->orWhere('router_id', $new_equipo->id)->first() : null;
        $old_contrato_router = ($new_router) ? Contrato::where('num_equipo', $new_router->id)->orWhere('router_id', $new_router->id)->first() : null;
        ### Si los new_equipo y new_router son vacios retornar error
        if (($contrato->tipo === 0 || $contrato->tipo === 1) && !$new_equipo) {
            $respuesta['error'][] = 'Se Requiere MacAddress de Equipo.';
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        } elseif ($contrato->tipo === 2 && !$new_router && !$new_equipo)   {
            $respuesta['error'][] = 'Se Requiere MacAddress de Equipo y/o de Router.';
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        } elseif (($contrato->tipo === 3) && !$new_router) {
            $respuesta['error'][] = 'Se Requiere MacAddress de Router.';
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }
        ## Si new equipo es panel o pertenece a exception
            ## Volver a modificarContrato con mensaje de error
        if ($new_equipo && $panel = \App\Models\Panel::where('id_equipo', $new_equipo->id)->where('activo', true)->first()) {
            $respuesta['error'][] = 'Antena ' . $request->mac_address . ' es un panel ACTIVO: ' . $panel->ssid;
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }
        if ($new_equipo && $exception = \App\Models\Mac_address_exception::where('equipo_id', $new_equipo->id)->first()) {
            $respuesta['error'][] = 'Antena ' . $request->mac_address . ' pertenece a una exception: ' . $exception->description;
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }
        ## Si new router es panel o pertenece a exception
            ## Volver a modificarContrato con mensaje de error
        if ($new_router && $panel = \App\Models\Panel::where('id_equipo', $new_router->id)->where('activo', true)->first()) {
            $respuesta['error'][] = 'Router ' . $request->mac_address . ' es un panel ACTIVO: ' . $panel->ssid;
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }
        if ($new_router && $exception = \App\Models\Mac_address_exception::where('equipo_id', $new_router->id)->first()) {
            $respuesta['error'][] = 'Router ' . $request->mac_address . ' pertenece a una exception: ' . $exception->description;
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }


        ### SI el equipo esta vinculado con un contrato
         ### Cambiar ID de equipo de viejo a nuevo en contrato
        if ($old_contrato_equipo) {
            if ($old_contrato_equipo->id === $contrato->id) {
                $respuesta['success'][] = 'Antena ' . $request->mac_address . ' pertenece al contrato de: ' . $old_contrato_equipo->relCliente->getNomYApe() . ', será reprogramado.';
                $no_borrar_equipo = true;
            } elseif ($old_contrato_equipo->baja) {
                ### Cambiar num_equipo en contrato anterior por router default (157)
                $old_contrato_equipo->num_equipo = 157;
                $old_contrato_equipo->save();
                $respuesta['success'][] = 'Antena ' . $request->mac_address . ' fue desvinculada del contrato de: ' . $old_contrato_equipo->relCliente->getNomYApe() . ', N°: ' . $old_contrato_equipo->id;
            } else {
                $respuesta['error'][] = 'Antena ' . $request->mac_address . ' pertenece al contrato activo de: ' . $old_contrato_equipo->relCliente->getNomYApe() . ', N°: ' . $old_contrato_equipo->id;
                return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
            }
            
        }
        if ($old_contrato_router) {
            if ($old_contrato_router->id === $contrato->id) {
                $respuesta['success'][] = 'Router ' . $request->mac_address_2 . ' pertenece al contrato de: ' . $old_contrato_router->relCliente->getNomYApe() . ', será reprogramado.';
                $no_borrar_router = true;
            } elseif ($old_contrato_router->baja) {
                ### Cambiar roputer_id en contrato anterior por null
                $old_contrato_router->router_id = null;
                $old_contrato_router->save();
                $respuesta['success'][] = 'Router ' . $request->mac_address_2 . ' fue desvinculada del contrato de: ' . $old_contrato_router->relCliente->getNomYApe() . ', N°: ' . $old_contrato_router->id;
            } else {
                $respuesta['error'][] = 'Router ' . $request->mac_address_2 . ' pertenece al contrato activo de: ' . $old_contrato_router->relCliente->getNomYApe() . ', N°: ' . $old_contrato_router->id;
                return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
            }
            
        }

        ### En equipo viejo en contrato agregar comentarios, 'Ex equipo de: De Tal, Fulano'
        if ((($new_equipo && $contrato->num_equipo) || ($contrato->num_equipo && $contrato->tipo === 3)) && !isset($no_borrar_equipo)) {
            $contrato->relEquipo->unlinkContract ($contrato->relCliente->getNomYApe());
        }
        if ((($new_router && $contrato->router_id) || (($contrato->router_id && ($contrato->tipo === 1 || $contrato->tipo === 0)))) && !isset($no_borrar_router)  ) {
            $contrato->relRouter->unlinkContract ($contrato->relCliente->getNomYApe());
        }
        ### quitar mac del panel solo
        if ($contrato->num_equipo !== 157) {
            $this->analizarRta($rta = $contrato->modificarMac(1)) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta;
        }
        
        ### Unlink equipo y/o router antiguos de Mikrotik
        $this->analizarRta($rta = $contrato->removeContratoGateway()) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta;
        
        ### Cambiar equipo en contrato solicitado
        if (isset($no_borrar_equipo)) {

            ### Cambiar por un IF
            $contrato->num_equipo = 157;
            $contrato->save();
            $contrato->refresh();
            if ($rta = $new_equipo->changeEquipoStatus(true)) {
                $respuesta['success'][] = $rta;
            } else {
                $respuesta['error'][] = 'No se pudo asignar a IP, en Equipo con MacAddress: ' . $new_equipo->mac_address;
                return redirect ('/modificarContrato/' . $request['id'])->with('mensaje', $respuesta);
            }
            
            $contrato->num_equipo = $new_equipo->id;
            $grabar = true;
            $cambios[] = 'Equipo: Sin cambios, se reprograma: ' . $new_equipo->mac_address . ' con IP ' . $new_equipo->ip;
        }
        elseif ( ($contrato->tipo === 0 || $contrato->tipo === 1 || $contrato->tipo === 2) )
        {
            if($contrato->num_equipo !== 157)
            {
                $old_macaddress = $contrato->relEquipo->mac_address;
            } else {
                $old_macaddress = 'Equipo Default';
            }
            ### Cambiar estado de equipo y/o router nuevos
            if ($new_equipo && !$rta = $new_equipo->changeEquipoStatus(true)) {
                $respuesta['error'][] = 'No se pudo asignar a IP, en Equipo con MacAddress: ' . $new_equipo->mac_address;
                return redirect ('/modificarContrato/' . $request['id'])->with('mensaje', $respuesta);
            } else {
                $this->analizarRta($rta) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta;
            }
            $cambios[] = 'Equipo: ' . $new_equipo->mac_address . ' X ' . $old_macaddress;
            $contrato->num_equipo = $new_equipo->id;
            if ($contrato->tipo !== 2 && $contrato->router_id !== null) {
                $cambios[] = ($contrato->relRouter->mac_address ? 'Router' . $contrato->relRouter->mac_address . ' X NULL' : '');
                $contrato->router_id = null;
            }
            $grabar = true;
        }

        if (isset($no_borrar_router)) {
            $contrato->router_id = null;
            $contrato->save();
            $contrato->refresh();
            $this->analizarRta($rta = $new_router->changeEquipoStatus(true)) ? $respuesta['success'][] = $rta : $respuesta['error'][] = 'No se pudo asignar a IP, en Equipo con MacAddress: ' . $new_equipo->mac_address;
            $contrato->router_id = $new_router->id;
            $grabar = true;
            $cambios[] = 'Router: Sin cambios, se reprograma: ' . $new_router->mac_address . ' con IP ' . $new_router->ip;
        }
        elseif ( ($contrato->tipo === 2 || $contrato->tipo === 3) )
        {
            if ($new_router) {
                $old_macaddress = $contrato->relRouter ? $contrato->relRouter->mac_address : 'NULL';
            } else {
                $old_macaddress = 'NULL';
            }
            ### Cambiar estado de equipo y/o router nuevos
            if ($new_router && !$rta_router = $new_router->changeEquipoStatus(true)) {
                $respuesta['error'][] = 'No se pudo asignar a IP, en Equipo con MacAddress: ' . $new_router->mac_address;
                return redirect ('/modificarContrato/' . $request['id'])->with('mensaje', $respuesta);
            } else {
                $this->analizarRta($rta_router) ? $respuesta['success'][] = $rta_router : $respuesta['error'][] = $rta_router;
            }
            $cambios[] = $new_router->mac_address . ' X ' . $old_macaddress ;
            $contrato->router_id = $new_router->id;
            $grabar = true;
            if ($contrato->tipo === 3 && $contrato->num_equipo !== 157)
            {
                $old_macaddress = $contrato->relEquipo->mac_address;
                $cambios[] = 'Equipo: ' . $contrato->relEquipo->mac_address . ' X Equipo Default';
                $contrato->num_equipo = 157;
            }
        }
        if (isset($grabar)) {
            $contrato->save();
            $contrato->refresh();
        }

        ### Al nuevo equipo cambiar nombre por nombre del contrato.
        if ($new_equipo) {
            $new_equipo->nombre = $contrato->relCliente->getNomYApe();
            $new_equipo->save();
        }
        if ($new_router) {
            $new_router->nombre = $contrato->relCliente->getNomYApe();
            $new_router->save();
        }
        
        ### Programar antena en Panel
        if ($contrato->tipo === 0 || $contrato->tipo === 1 || $contrato->tipo === 2) {
            $this->analizarRta($rta = $contrato->modificarMac(0)) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta;
        }

        ### Reprogramar mikrotik
        $this->analizarRta($rta = $contrato->modifyContratoGateway()) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta;
        $this->analizarRta($rta = $contrato->renewIPAntenaClient()) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta;

        ### Crear ticket registrando cambio
        $descripcion = Auth::user()->name . ' realiza Cambio de equipo y/o router. ' . implode('. ', $cambios);
        $ticket = \App\Models\Issue::create([
            'titulo_id' => 12,
            'descripcion' => $descripcion,
            'asignado_id' => Auth::id(),
            'creator_id' => 1,
            'cliente_id' => $contrato->relCliente->id,
            'contrato_id' => $contrato->id,
            'closed' => true]);
        $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en Equipo y/o Router del contrato N°: ' . $contrato->id;
        
        return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        
    }
    public function updateCoordenadas(Request $request)
    {
        $contrato = Contrato::find($request->id);
        $respuesta['success'][] = $descripcion = 'Cambio en contrato N°: ' . $contrato->id . ' de coordenadas: ' . ($request->coordenadas ? $request->coordenadas : 'NULL') . ' por '  . ($contrato->relDireccion->coordenadas ? $contrato->relDireccion->coordenadas : 'NULL') . '.';
        $contrato->relDireccion->coordenadas = $request->coordenadas;
        $ticket = \App\Models\Issue::create([
                    'titulo_id' => 13,
                    'descripcion' => $descripcion,
                    'asignado_id' => Auth::id(),
                    'creator_id' => 1,
                    'cliente_id' => $contrato->relCliente->id,
                    'contrato_id' => $contrato->id,
                    'closed' => true]);
        $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en titular del contrato N°: ' . $contrato->id;
        $contrato->relDireccion->save();
        return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
    }
    public function updateCliente(Request $request) {
        $contrato = Contrato::find($request->id);
        if ($nuevoCliente = Cliente::find($request->genesys_id)) {
            if ($nuevoCliente->id === $contrato->num_cliente) {
                $respuesta['error'][] = 'El Genesys ID Cliente es igual al actual.';
                return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
            }
            $anterior = $contrato->num_cliente . ' (' . $contrato->relCliente->getNomYApe(true) . ')';
            $contrato->num_cliente = $request->genesys_id;
            $posterior = $nuevoCliente->id . ' (' . $nuevoCliente->getNomYApe(true) . ')';
            
            $respuesta['success'][] = $descripcion = 'Se actualizó cliente de: ' . $anterior . ' a ' . $posterior;
            $contrato->save();
            $contrato->refresh();
            $ticket = \App\Models\Issue::create([
                        'titulo_id' => 9,
                        'descripcion' => $descripcion,
                        'asignado_id' => Auth::id(),
                        'creator_id' => 1,
                        'cliente_id' => $contrato->relCliente->id,
                        'contrato_id' => $contrato->id,
                        'closed' => true]);
            $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en titular del contrato N°: ' . $contrato->id;
            return redirect('/inicio')->with('mensaje', $respuesta);
        } else {
            $respuesta['error'][] = 'El cliente con Genesys ID: ' . $request->genesys_id . ' no está cargado.';
            return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
        }
    }
    public function updatePanel (Request $request)
    {
        $contrato = Contrato::find($request->id);
        $anterior = $contrato->relPanel;
        $contrato->num_panel = $request['num_panel'];
        $contrato->save();
        $contrato->refresh();
        $descripcion = Auth::user()->name . ' realiza prueba de ' . ($respuesta['info'][] = 'cambio Panel: ' . $anterior->ssid . ' POR ' . $contrato->relPanel->ssid . '. |' . $anterior->id . '|');
        $this->analizarRta($rta = $contrato->modificarMac(0)) ? $respuesta['success'][] = $rta : $respuesta['error'][] = $rta; ### modificarMac = 'ope' => 1 = Del, 0 = Add
        $ticket = \App\Models\Issue::create([
                    'titulo_id' => 11,
                    'descripcion' => $descripcion,
                    'asignado_id' => Auth::id(),
                    'creator_id' => 1,
                    'cliente_id' => $contrato->relCliente->id,
                    'contrato_id' => $contrato->id,
                    'closed' => false]);
        $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio de Panel del contrato N°: ' . $contrato->id;
        return redirect('/modificarContrato/' . $request->id)->with('mensaje', $respuesta);
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
    public function equipoDefault(Request $request)
    {
        $contrato = Contrato::find($request->id);
        if ($contrato->baja && $contrato->num_equipo != 157) {
            $descripcion = 'Se cambió Equipo ID: ' . $contrato->num_equipo . ', MacAddress: ' . $contrato->relEquipo->mac_address . ', por Equipo default seteado.';
            $contrato->num_equipo = 157;
            $respuesta['success'][] = 'Cambio en contrato de: ' . $contrato->relCliente->getNomYApe() . ', Equipo default seteado.';
            $ticket = \App\Models\Issue::create([
                'titulo_id' => 12,
                'descripcion' => $descripcion,
                'asignado_id' => Auth::id(),
                'creator_id' => 1,
                'cliente_id' => $contrato->relCliente->id,
                'contrato_id' => $contrato->id,
                'closed' => true]);
            $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en titular del contrato N°: ' . $contrato->id;
            $contrato->save();
        } else {
            $respuesta['error'][] = 'Sin Cambios en contrato de: ' . $contrato->relCliente->getNomYApe() . ', contrato no está dado de baja o ya está seteado el equipo default.';
        }
        return redirect('/inicio')->with('mensaje', $respuesta);
    }

    ################# Métodos de Gateway ####################################

    public function renewIPAntenaClient($contrato)
    {
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
