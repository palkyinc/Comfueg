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
            date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
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
        $respuesta= '';
        $alta = Alta::find($request->input('alta_id'));
        $macadress = (null !== $request->input('mac_address')) ? $this->validarEquipo($request, true) : null;
        if ((null != $request->input('alta_equipo')) && $macadress){
            if(!$errores = $this->validarEquipo($request)){
                $equipo = $this->guardarEquipo($request);
            }
        }elseif ($macadress && 
            $equipo = Equipo::where('mac_address', ($macadress = strtoupper($macadress)))->first()
            ) {
                $contrato = Contrato::where('num_equipo', $equipo->id)->first();
                $panel = Panel::where('id_equipo', $equipo->id)->first();
            }
        $dispositivos = Producto::get();
        $antenas = Antena::get();
        $paneles = Panel::where('activo', true)->where('rol', 'PANEL')->orderBy('num_site')->get();
        //dd($paneles);
        return view('programarAlta', [
                    'alta' => $alta,
                    'macaddress' => $macadress,
                    'equipo' => $equipo ?? null,
                    'contrato' => $contrato ?? null,
                    'panel' => $panel ?? null,
                    'dispositivos' => $dispositivos ?? null,
                    'antenas' => $antenas ?? null,
                    'paneles' => $paneles ?? null,
                    'errores' => $errores ?? null,
        ]);
    }

    private function guardarEquipo(Request $request) {
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
        return $equipo;
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
    
    ### API-Rest Metodos

    public function getAltaPorId ($id) {
        ($alta = ($id) ? (Alta::select('id', 'cliente_id', 'direccion_id', 'plan_id', 'comentarios')->find($id)) : null);
        return response()->json($alta, 200);
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
    public function cancelApi (Request $request){
        ($alta = Alta::find($request->input('id')));
        $alta->anulado = true;
        $alta->save();
        $respuesta['success'][] = 'Se anulo Alta del Cliente: ' . $alta->relCliente->getNomYApe();
        return redirect ('adminAltas')->with('mensaje', $respuesta);
    }
}
