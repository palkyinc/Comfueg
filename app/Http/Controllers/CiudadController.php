<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = strtoupper($request->input('nombre'));
        $ciudades = Ciudad::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$nombre}%"])
            ->paginate(10);
        return view('adminCiudades', ['ciudades' => $ciudades, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarCiudad', ['datos' => 'active']);
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
        $Ciudad = new Ciudad;
        $Ciudad->nombre = $request->input('nombre');
        $Ciudad->save();
        $respuesta[] = 'Ciudad se creo correctamente';
        return redirect('/adminCiudades')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ciudad  $ciudad
     * @return \Illuminate\Http\Response
     */
    public function show(Ciudad $ciudad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ciudad  $ciudad
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Ciudad = Ciudad::find($id);
        return view('modificarCiudad', ['elemento' => $Ciudad, 'datos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ciudad  $ciudad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $nombre = $request->input('nombre');
        $ciudad = Ciudad::find($request->input('id'));
        $this->validar($request, $ciudad->id);
        $ciudad->nombre = $nombre;
        $respuesta[] = 'Se cambió con exito:';
        if ($ciudad->nombre != $ciudad->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $ciudad->getOriginal()['nombre'] . ' POR ' . $ciudad->nombre;
        }
        $ciudad->save();
        return redirect('adminCiudades')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idCiudad = "")
    {
        if ($idCiudad) {
            $condicion = 'required|min:2|max:45|unique:ciudades,nombre,' . $idCiudad;
        } else {
            $condicion = 'required|min:2|max:45|unique:ciudades,nombre';
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
     * @param  \App\Models\Ciudad  $ciudad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ciudad $ciudad)
    {
        //
    }

    public function search()
    {
        $ciudades = Ciudad::get();
        return response()->json($ciudades);
    }
}
