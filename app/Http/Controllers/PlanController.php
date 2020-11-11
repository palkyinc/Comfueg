<?php

namespace App\Http\Controllers;

use App\Models\Plan;
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
        return view('adminPlanes', ['planes' => $planes, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarPlan', ['datos' => 'active']);
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
        //
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
        return view('modificarPlan', ['elemento' => $Plan, 'datos' => 'active']);
    }

    public function validar(Request $request)
    {
        //dd($request->input());
        $request->validate(
            [
                'nombre' => 'required|min:2|max:20',
                'descripcion' => 'max:100',
                'bajada' => 'required|numeric|min:1|max:99999',
                'subida' => 'required|numeric|min:1|max:99999'
            ]
        );
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
        $nombre = $request->input('nombre');
        $bajada = $request->input('bajada');
        $subida = $request->input('subida');
        $descripcion = $request->input('descripcion');
        $plan = Plan::find($request->input('id'));
        $this->validar($request);
        $plan->nombre = $nombre;
        $plan->descripcion = $descripcion;
        $plan->bajada = $bajada;
        $plan->subida = $subida;
        $respuesta[] = 'Se cambió con exito:';
        if ($plan->nombre != $plan->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $plan->getOriginal()['nombre'] . ' POR ' . $plan->nombre;
        }
        if ($plan->descripcion != $plan->getOriginal()['descripcion']) {
            $respuesta[] = ' Descripción: ' . $plan->getOriginal()['descripcion'] . ' POR ' . $plan->descripcion;
        }
        if ($plan->bajada != $plan->getOriginal()['bajada']) {
            $respuesta[] = ' Bajada: ' . $plan->getOriginal()['bajada'] . ' POR ' . $plan->bajada;
        }
        if ($plan->subida != $plan->getOriginal()['subida']) {
            $respuesta[] = ' Subida: ' . $plan->getOriginal()['subida'] . ' POR ' . $plan->subida;
        }
        $plan->save();
        return redirect('adminPlanes')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        //
    }
}