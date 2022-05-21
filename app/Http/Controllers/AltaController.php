<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Contrato;
use App\Models\Panel;
use App\Models\Producto;
use App\Models\Antena;
use Illuminate\Http\Request;
use Axiom\Rules\MacAddress;
use App\Models\Alta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use DateTime;
use DB;
use Illuminate\Support\Facades\Validator;

class AltaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ($instaladas = ($request->input('instaladas')) ? true : false);
        ($anuladas = ($request->input('anuladas')) ? true : false);
        $apellido = strtoupper($request->input('apellido'));
        $calle = strtoupper($request->input('calle'));
        $altas = Alta::
            whereRelation('relDireccion.relCalle', DB::raw('upper(nombre)'), 'like', ["%{$calle}%"])->
            whereRelation('relCliente', 'apellido', 'like', ["%{$apellido}%"])->
            where('instalado', $instaladas)->
            where('anulado', $anuladas)->
            paginate(10);
        return view ('adminAltas', [
            'internet' => 'active',
            'altas' => $altas,
            'instaladas' => $instaladas,
            'anuladas' => $anuladas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $modify = false)
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $this->validar($request);
        ($alta = ($modify) ? Alta::find($request->input('alta_id')) : new Alta);
        $alta->cliente_id = $request->input('cliente_id');
        $alta->direccion_id = $request->input('direccion_id');
        $alta->plan_id = $request->input('plan_id');
        $alta->comentarios = $request->input('comentarios');
        if (!$modify) {
            $alta->programado = false;
            $alta->instalado = false;
            $alta->anulado = false;
            $dias_instalacion = 3;
            if (date('w', strtotime(date('Ymd')." + $dias_instalacion days")) == 0) {
                $dias_instalacion += 1;
            } elseif (date('w', strtotime(date('Ymd')." + $dias_instalacion days")) == 6) {
                $dias_instalacion += 2;
            }
            $alta->instalacion_fecha = date('Ymd', strtotime(date('Ymd')." + $dias_instalacion days"));
        }
        return $alta;
    }

    public function validar(Request $request)
    {
        $aValidar = [
            'cliente_id' => 'required|numeric|min:1|max:99999',
            'direccion_id' => 'required|numeric|min:1|max:99999',
            'plan_id' => 'required|numeric|min:1|max:99999',
            'comentario' => 'nullable|min:3|max:500'
        ];
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateInstalldate(Request $request)
    {
        $alta = Alta:: find($request->input('id'));
        $alta->instalacion_fecha = $request->input('nuevaFecha');
        $alta->save();
        $respuesta['success'][] = 'Se actualizó la fecha de instalación correctamente a ' . $alta->instalacion_fecha . ' del Cliente: ' . $alta->relCliente->getNomYApe();
        return redirect ('adminAltas')->with('mensaje', $respuesta);
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
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function programming(Request $request)
    {
        //dd($request);
        $alta = Alta::find($request->input('alta_id'));
        ($paso = (null !== $request->input('paso')) ? $request->input('paso') : 0);
        $tipo_contrato = (null !== $request->input('tipo_contrato')) ? $request->input('tipo_contrato') : null;
        switch ($tipo_contrato) {
            case 1: //standard
                $titulo_paso2 = "Ingresar MacAddress de Antena Ubiquiti";
                $macadress = (null !== $request->input('mac_address')) ? $this->validarEquipo($request, true) : null;
                switch ($paso) {
                    case 1:
                        $paso = 2;
                        $datos_cliente = true;
                        break;
                    
                    case 2:
                        if ($macadress) {
                            if ($equipo = Equipo::where('mac_address', ($macadress = strtoupper($macadress)))->first()
                            ) {
                                $contrato = Contrato::where('num_equipo', $equipo->id)->first();
                                $panel = Panel::where('id_equipo', $equipo->id)->first();
                                if ($contrato || $panel){
                                    $paso = 3;
                                }else {
                                    $paso = 6;
                                }
                            }else {
                                $paso = 5;
                            }
                        }
                        break;
                    case 5:
                        if(!$errores = $this->validarEquipo($request)){
                            $equipo = $this->guardarEquipo($request);
                            $paso = 6;
                        }
                        break;
                    case 6:
                        if (!$errores = $this->validarPaso6($request)){
                            return redirect('/adminAltas')->with('mensaje', $this->crearContrato($request));
                        } else {
                            $paso = 0;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                
            
            case '2': //Bridge
                # code...
                break;
            
            case '3': //solo router
                # code...
                break;
            
            default:
                if ($paso === 0) {
                    $paso = 1;
                    $datos_cliente = true;
                }
                break;
        }
        if ($paso == 5){
            $dispositivos = Producto::get();
            $antenas = Antena::get();
        }
        if ($paso ==6) {
            $paneles = Panel::where('activo', true)->where('rol', 'PANEL')->orderBy('num_site')->get();
        }
        //dd($alta);
        return view('programarAlta', [
                    'alta' => $alta ?? null,
                    'macaddress' => $macadress ?? null,
                    'equipo' => $equipo ?? false,
                    'contrato' => $contrato ?? false,
                    'panel' => $panel ?? false,
                    'dispositivos' => $dispositivos ?? [],
                    'antenas' => $antenas ?? [],
                    'paneles' => $paneles ?? [],
                    'errores' => $errores ?? false,
                    'router' => $router ?? false,
                    'paso' => $paso,
                    'tipo_contrato' => $tipo_contrato ?? null,
                    'titulo_paso2' => $titulo_paso2 ?? '',
                    'datos_cliente' => $datos_cliente ?? false
        ]);
    }

    private function crearContrato (Request $request) 
    {
        if (!Panel::where('activo', true)->where('rol', 'PANEL')->find($request->input('num_panel')) )
            {
                $mensaje['error'][] = 'ERROR: Panel no existente. Nada fue guardado no creado.';
            } 
            else 
            {
                    $alta = Alta::find($request->input('alta_id'));
                    $nuevo_contrato = New Contrato;
                    $nuevo_contrato->num_cliente = $alta->cliente_id;
                    $nuevo_contrato->num_plan = $alta->plan_id;
                    $nuevo_contrato->id_direccion = $alta->direccion_id;
                    $nuevo_contrato->num_equipo = $request->input('equipo_id');
                    $nuevo_contrato->num_panel = $request->input('num_panel');
                    $nuevo_contrato->tipo = $request->input('tipo_contrato');
                    $nuevo_contrato->activo = false;
                    $nuevo_contrato->baja = false;
                    $nuevo_contrato->save();
                    $alta->programado = true;
                    $alta->save();
                    $contrato = $nuevo_contrato->fresh();
                    $mensaje['success'][] = 'Alta de ' . $contrato->relCliente->getNomYApe() . ' programada.';
                    ## set nuevo IP para el equipo
                    if(!$contrato->RelEquipo->setIpAuto()) {
                            $mensaje['error'][] = 'ERROR al intentar asignar IP al equipo. NOTA: Gateway(' . $contrato->relPlan->relPanel->relEquipo->ip . ') no programados';
                        }else {
                            $mensaje['success'][] = 'EXITO. IP asignado automáticamente.';
                            $contrato = $contrato->fresh();
                            $mensaje['success'][] = 'VER. contrato nuevo en Mikrotik, probar y habilitar habilitar método';
                            /* $rta = $contrato->createContratoGateway();
                            if (!$this->analizarRta($rta)){
                                $mensaje['error'][] = $rta;
                            } else {
                                $mensaje['success'][] = $rta;
                            } */
                            $mensaje['success'][] = 'VER: Deshabilita el contrato, probar y habilitar método';
                            /* $rta = $contrato->changeStateContratoGateway();
                            if (!$this->analizarRta($rta)){
                                $mensaje['error'][] = $rta;
                            } */
                        }
                    $mensaje['success'][] = 'VER Mac en Ubiquiti, probar y habilitar método.';
                    /* $rta = $contrato->modificarMac(0);
                    if (!$this->analizarRta($rta)){
                        $mensaje['error'][] = $rta;
                    } else {
                        $mensaje['success'][] = $rta;
                    } */
            }
        //dd($mensaje);
        return $mensaje;
    }

    private function guardarEquipo(Request $request) {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $equipo = new Equipo;
        $equipo->nombre = $request->input('nombre');
        $equipo->mac_address = $request->input('mac_address');
        $equipo->num_dispositivo = $request->input('num_dispositivo');
        $equipo->num_antena = $request->input('num_antena');
        $equipo->comentario = $request->input('comentario');
        $equipo->fecha_alta = new DateTime();
        if (null === $request->input('ip')) {
            $equipo->ip = '0.0.0.0';
        } else {
            $equipo->ip = $request->input('ip');
        }
        $equipo->save();
        return $equipo->fresh();
    }

    private function validarEquipo(Request $request, $validate_mac = false)
    {
        if ($validate_mac) {
            return (filter_var($request->input('mac_address'), FILTER_VALIDATE_MAC));
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'nombre' => 'required|min:2|max:45',
                    'num_dispositivo' => 'required|numeric|min:1|max:99999',
                    'num_antena' => 'required|numeric|min:1|max:99999',
                    'ip' => 'nullable|ipv4',
                    'comentario' => 'max:65535'
                ]
            );
            if ($validator->fails()) {
                return($validator->errors());
            }else {
                return false;
            }
        } 
    }
    
    private function analizarRta ($rta){
        if ($wordTest = str_split($rta, 5)[0] === 'EXITO') {
            return true;
        } elseif ($wordTest === 'ERROR') {
            return false;
        }
        return null;
        
    }
    
    private function validarPaso6 (Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'paso' => 'required|numeric|min:1|max:6',
                'num_panel' => 'required|numeric|min:1|max:99999',
                'tipo_contrato' => 'required|numeric|min:1|max:4',
                'alta_id' => 'required|numeric|min:1|max:99999',
                'equipo_id' => 'required|numeric|min:1|max:99999'
            ]
        );
        if ($validator->fails()) {
            return($validator->errors());
        }else {
            return false;
        }
    }
    
    public function cancelApi (Request $request){
        ($alta = Alta::find($request->input('id')));
        if ($alta->anulado) {
            $alta->anulado = false;
            $respuesta['success'][] = 'Se anuló Alta del Cliente: ' . $alta->relCliente->getNomYApe();
        } else {
            $alta->anulado = true;
            $respuesta['success'][] = 'Se restableció Alta del Cliente: ' . $alta->relCliente->getNomYApe();
        }
        $alta->save();
        return redirect ('adminAltas')->with('mensaje', $respuesta);
    }

    ### API-Rest Metodos

    public function getAltaPorId ($id) {
        ($alta = ($id) ? (Alta::select('id', 'cliente_id', 'direccion_id', 'plan_id', 'comentarios', 'created_at', 'instalacion_fecha')->find($id)) : null);
        return response()->json($alta, 200);
    }

    public function vueIndexProgramarAlta (Request $request)
    {
        return view('programarAlta', [
            'internet' => 'active',
            'alta' => $request->input('alta_id'),
            'website' => env('DOMINIO_COMFUEG'),
            'vuejs' => env('VUEJS_VERSION')
        ]);
    }

    public function vueIndex2 ($id = null)
    {
        return view('agregarAlta', [
            'internet' => 'active',
            'alta' => $id,
            'website' => env('DOMINIO_COMFUEG'),
            'vuejs' => env('VUEJS_VERSION')
        ]);
    }
    public function storeApi (Request $request){
        $alta = $this->store($request);
        $alta->save();
        return response()->json(true, 200);
    }
    public function updateApi (Request $request){
        $alta = $this->store($request, true);
        $alta->save();
        return response()->json(true, 200);
    }
    
}
