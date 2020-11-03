<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use Illuminate\Http\Request;

class BarrioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = strtoupper($request->input('nombre'));
        $barrios = Barrio::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$nombre}%"])
            ->paginate(10);
        return view('adminBarrios', ['barrios' => $barrios, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarBarrio');
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
        $Barrio = new Barrio;
        $Barrio->nombre = $request->input('nombre');
        $Barrio->save();
        $respuesta[] = 'Barrio se creo correctamente';
        return redirect('/adminBarrios')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function show(Barrio $barrio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Barrio = Barrio::find($id);
        return view('modificarBarrio', ['elemento' => $Barrio, 'datos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $nombre = $request->input('nombre');
        $barrio = Barrio::find($request->input('id'));
        $this->validar($request, $barrio->id);
        $barrio->nombre = $nombre;
        $respuesta[] = 'Se cambió con exito:';
        if ($barrio->nombre != $barrio->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $barrio->getOriginal()['nombre'] . ' POR ' . $barrio->nombre;
        }
        $barrio->save();
        return redirect('adminBarrios')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idBarrio = "")
    {
        if ($idBarrio) {
            $condicion = 'required|min:2|max:45|unique:barrios,nombre,' . $idBarrio;
        } else {
            $condicion = 'required|min:2|max:45|unique:barrios,nombre';
        }
        $request->validate(
            [
                'nombre' => $condicion
            ],
            [
                'nombre.required' => 'El campo Nombre es obligatorio',
                'nombre.unique' => 'El campo Nombre no puede repetirse.',
                'nombre.min' => 'El campo Nombre debe tener al menos 2 caractéres',
                'nombre.max' => 'El campo Nombre debe tener 45 caractéres como máximo'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barrio $barrio)
    {
        //
    }
    public function search () {
        $barrios = Barrio::select('nombre')->get();
        return response()->json($barrios);
    }
}// fin de barrio
