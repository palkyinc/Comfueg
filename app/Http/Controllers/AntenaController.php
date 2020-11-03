<?php

namespace App\Http\Controllers;

use App\Models\Antena;
use Illuminate\Http\Request;

class AntenaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $codComfueg = strtoupper($request->input('codComfueg'));
        $descripcion = strtoupper($request->input('descripcion'));
        $antenas = Antena::select("*")
            ->whereRaw("UPPER(cod_comfueg) LIKE (?)", ["%{$codComfueg}%"])
            ->whereRaw("UPPER(descripcion) LIKE (?)", ["%{$descripcion}%"])
            ->paginate(10);
        return view ('adminAntenas', ['antenas' => $antenas, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarAntena');
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
        $Antena = new Antena;
        $Antena->descripcion = $request->input('descripcion');
        $Antena->cod_comfueg = $request->input('cod_comfueg');
        $Antena->save();
        $respuesta[] = 'Antena se creo correctamente';
        return redirect('/adminAntenas')->with('mensaje', $respuesta);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Antena  $antena
     * @return \Illuminate\Http\Response
     */
    public function show(Antena $antena)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Antena  $antena
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Antena = Antena::find($id);
        return view ('modificarAntena', ['elemento' => $Antena, 'datos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Antena  $antena
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $descripcion = $request->input('descripcion');
        $cod_comfueg = $request->input('cod_comfueg');
        $antena = Antena::find( $request->input('id'));
        $this->validar($request, $antena->id);
        $antena->descripcion = $descripcion;
        $antena->cod_comfueg = $cod_comfueg;
        $respuesta[] = 'Se cambió con exito:';
        if ($antena->descripcion != $antena->getOriginal()['descripcion']) {
            $respuesta[] = ' Descripción: ' . $antena->getOriginal()['descripcion'] . ' POR ' . $antena->descripcion;
        }
        if ($antena->cod_comfueg != $antena->getOriginal()['cod_comfueg']) {
            $respuesta[] = ' Cod Comfueg: ' . $antena->getOriginal()['cod_comfueg'] . ' POR ' . $antena->cod_comfueg;
        }
        $antena->save();
        return redirect('adminAntenas')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idAntena = "")
    {
        if ($idAntena)
        {
            $condicion = 'required|min:2|max:45|unique:antenas,cod_comfueg,' . $idAntena;
        }else
            {
                $condicion = 'required|min:2|max:45|unique:antenas,cod_comfueg';
            }
        $request->validate(
            [
                'descripcion' => 'required|min:2|max:30',
                'cod_comfueg' => $condicion
            ],
            [
                'descripcion.required' => 'El campo Descripción es obligatorio',
                'descripcion.min' => 'El campo Descripción debe tener al menos 2 caractéres',
                'descripcion.max' => 'El campo Descripción debe tener 30 caractéres como máximo',
                'cod_comfueg.required' => 'El campo Código Comfueg es obligatorio',
                'cod_comfueg.unique' => 'El campo Código Comfueg no puede repetirse.',
                'cod_comfueg.min' => 'El campo Código Comfueg debe tener al menos 2 caractéres',
                'cod_comfueg.max' => 'El campo Código Comfueg debe tener 45 caractéres como máximo'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Antena  $antena
     * @return \Illuminate\Http\Response
     */
    public function destroy(Antena $antena)
    {
        //
    }
}//fin de la clase
