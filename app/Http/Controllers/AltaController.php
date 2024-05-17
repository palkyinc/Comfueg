<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
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
    public function store(Request $request, $modify = false)
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $this->validar($request);
        ($alta = ($modify) ? Alta::find($request->input('alta_id')) : new Alta);
        if (!$modify) {
            $alta->creator = auth()->user()->id;
        }
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
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function updateInstalldate(Request $request)
    {
        $alta = Alta:: find($request->input('id'));
        $alta->instalacion_fecha = $request->input('nuevaFecha');
        $alta->save();
        $respuesta['success'][] = 'Se actualizó la fecha de instalación correctamente a ' . $alta->instalacion_fecha . ' del Cliente: ' . $alta->relCliente->getNomYApe();
        return redirect ('adminAltas')->with('mensaje', $respuesta);
    }
    public function destroy($id)
    {
        //
    }
    
    ### Api

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
        ($alta = ($id) ? (Alta::select('id', 'cliente_id', 'direccion_id', 'plan_id', 'comentarios', 'created_at', 'instalacion_fecha', 'creator')->find($id)) : null);
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
