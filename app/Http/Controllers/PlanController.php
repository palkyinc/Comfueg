<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use App\Models\Plan;
use App\Models\Contrato;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = strtoupper($request->input('nombre'));
        $planes = Plan::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$nombre}%"])
            ->paginate(10);
        $respuesta = null;
        foreach ($planes as $key => $plan) {
            if (!$plan->reconcileMikrotik())
            {
                $planes[$key]->reconcile = 1;
                $respuesta[] = 'El plan ' . $plan->nombre . ' debe reconciliarse. Debe borrar el gateway y volver a configurarlo.';
            }
        }
        return view('adminPlanes', ['planes' => $planes, 'providers' => 'active', 'warning' => $respuesta]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarPlan', ['providers' => 'active']);
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
        $Plan = new Plan;
        $Plan->nombre = $request->input('nombre');
        $Plan->bajada = $request->input('bajada');
        $Plan->subida = $request->input('subida');
        if ($rta = $this->validarTwo($request)) {
            return back()->withInput()->withErrors(['msg' => [$rta]]);
        }
        $Plan->mbt = $request->input('mbt') === null ? 0 : $request->input('mbt');
        $Plan->br = $request->input('br') === null ? 0 : $request->input('br');;
        $Plan->bth = $request->input('bth') === null ? 0 : $request->input('bth');;
        $Plan->descripcion = $request->input('descripcion');
        $Plan->save();
        $respuesta[] = 'Plan se creo correctamente';
        return redirect('/adminPlanes')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        ## 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $Plan = Plan::find($id);
        $gateway = Panel::where('rol','GATEWAY')->get();
        $gatewayReadonly = (count(Contrato::where('num_plan', $Plan->id)->get())) ? true : false;
        return view('modificarPlan', ['elemento' => $Plan, 'gateways' => $gateway, 'providers' => 'active', 'gatewayReadonly' => $gatewayReadonly]);
    }

    public function validar(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required|min:2|max:20',
                'descripcion' => 'max:100',
                'bajada' => 'required|numeric|min:1|max:99999',
                'subida' => 'required|numeric|min:1|max:99999',
                'mbt' => 'nullable|numeric|min:1|max:99999',
                'br' => 'nullable|numeric|min:101|max:1000',
                'bth' => 'nullable|numeric|min:50|max:100',
                'gateway_id' => 'nullable|numeric'
            ]
        );
    }
    public function validarTwo($request)
    {
        if ( ($request->input('mbt') && $request->input('br') && $request->input('bth')) || (!$request->input('mbt') && !$request->input('br') && !$request->input('bth')) ) {
            return false;
        } else {
            return 'Max Burst Time, Burst Rate, Burst Threshold deben completarse todos vacios o con datos.';
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $plan = Plan::find($request->input('id'));
        $this->validar($request);
        $plan->nombre = $request->input('nombre');
        $plan->descripcion = $request->input('descripcion');
        $plan->bajada = $request->input('bajada');
        $plan->subida = $request->input('subida');
        $plan->gateway_id = $request->input('gateway_id');
        if ($rta = $this->validarTwo($request)) {
            return back()->withInput()->withErrors(['msg' => [$rta]]);
        }
        $plan->mbt = $request->input('mbt') === null ? 0 : $request->input('mbt');
        $plan->br = $request->input('br') === null ? 0 : $request->input('br');;
        $plan->bth = $request->input('bth') === null ? 0 : $request->input('bth');;
        $modifyMikrotik = false;
        $respuesta[] = 'Se cambió con exito:';
        if ($plan->nombre != $plan->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $plan->getOriginal()['nombre'] . ' POR ' . $plan->nombre;
        }
        if ($plan->descripcion != $plan->getOriginal()['descripcion']) {
            $respuesta[] = ' Descripción: ' . $plan->getOriginal()['descripcion'] . ' POR ' . $plan->descripcion;
        }
        if ($plan->bajada != $plan->getOriginal()['bajada']) {
            $respuesta[] = ' Bajada: ' . $plan->getOriginal()['bajada'] . ' POR ' . $plan->bajada;
            $modifyMikrotik['bajada'] = true;
        }
        if ($plan->subida != $plan->getOriginal()['subida']) {
            $respuesta[] = ' Subida: ' . $plan->getOriginal()['subida'] . ' POR ' . $plan->subida;
            $modifyMikrotik['subida'] = true;
        }
        if ($plan->mbt != $plan->getOriginal()['mbt']) {
            $respuesta[] = ' Max Burst Time: ' . $plan->getOriginal()['mbt'] . ' POR ' . $plan->mbt;
            $modifyMikrotik['mbt'] = true;
        }
        if ($plan->br != $plan->getOriginal()['br']) {
            $respuesta[] = ' Burst Rate: ' . $plan->getOriginal()['br'] . ' POR ' . $plan->br;
            $modifyMikrotik['br'] = true;
        }
        if ($plan->bth != $plan->getOriginal()['bth']) {
            $respuesta[] = ' Burst Threshold: ' . $plan->getOriginal()['bth'] . ' POR ' . $plan->bth;
            $modifyMikrotik['bth'] = true;
        }
        if ($plan->gateway_id != $plan->getOriginal()['gateway_id']) {
            $respuesta[] = ' Gateway: ' . $plan->getOriginal()['gateway_id'] . ' POR ' . $plan->gateway_id;
            $modifyMikrotik['gateway'] = true;
        }
        if ($plan->modifyMikrotik($modifyMikrotik))
        {
            $respuesta[] = 'Gateway Actualizado OK!';
            $plan->save();
        }
        else
        {
            $respuesta = null;
            $respuesta[] = 'ERROR. Nada se ha actualizado.';
        }
        return redirect('adminPlanes')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy($plan_id)
    {
        $Plan = Plan::find($plan_id);
        $respuesta[] = ' Se eliminó correctamente el plan: ' . $Plan->nombre;
        $Plan->delete();
        return redirect('adminPlanes')->with('mensaje', $respuesta);
    }

    ### API-Rest

    public function getAllPlans () {
        $planes = Plan::where('gateway_id', '!=', null)->get();
        return response()->json($planes, 200);
    }
}