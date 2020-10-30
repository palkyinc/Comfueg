<?php

namespace App\Http\Controllers;

use App\Models\CodigoDeArea;
use Illuminate\Http\Request;

class CodigoDeAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $codigoDeArea = strtoupper($request->input('codigoDeArea'));
        $codigosDeArea = CodigoDeArea::select("*")
            ->whereRaw("UPPER(codigoDeArea) LIKE (?)", ["%{$codigoDeArea}%"])
            ->paginate(10);
        return view('adminCodigosDeArea', ['codigosDeArea' => $codigosDeArea, 'datos' => 'active']);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CodigoDeArea  $codigoDeArea
     * @return \Illuminate\Http\Response
     */
    public function show(CodigoDeArea $codigoDeArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CodigoDeArea  $codigoDeArea
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $CodigoDeArea = CodigoDeArea::find($id);
        return view('modificarCodigoDeArea', ['elemento' => $CodigoDeArea, 'datos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CodigoDeArea  $codigoDeArea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $codigoDeArea = $request->input('codigoDeArea');
        $provincia = $request->input('provincia');
        $localidades = $request->input('localidades');
        $codigoArea = CodigoDeArea::find($request->input('id'));
        $this->validar($request, $codigoArea);
        $codigoArea->codigoDeArea = $codigoDeArea;
        $codigoArea->provincia = $provincia;
        $codigoArea->localidades = $localidades;
        if ($codigoArea->codigoDeArea != $codigoArea->getOriginal()['codigoDeArea']) {
            $respuesta[] = ' Código De área: ' . $codigoArea->getOriginal()['codigoDeArea'] . ' POR ' . $codigoArea->codigoDeArea;
        }
        if ($codigoArea->provincia != $codigoArea->getOriginal()['provincia']) {
            $respuesta[] = ' Provincia: ' . $codigoArea->getOriginal()['provincia'] . ' POR ' . $codigoArea->provincia;
        }
        if ($codigoArea->localidades != $codigoArea->getOriginal()['localidades']) {
            $respuesta[] = ' Localidades: ' . $codigoArea->getOriginal()['localidades'] . ' POR ' . $codigoArea->localidades;
        }
        $codigoArea->save();
        return redirect('adminCodigosDeArea')->with('mensaje', 'Se cambió con exito ->' . $respuesta);
    }

    public function validar(Request $request, CodigoDeArea $codigoDeArea)
    {
        $request->validate(
            [
                'codigoDeArea' => 'required|numeric|min:11|max:9999|unique:codigosDeArea,codigoDeArea,' . $codigoDeArea->id,
                'provincia' => 'required|min:2|max:45',
                'localidad' => 'max:65535'
            ],
            [
                'codigoDeArea.required' => 'El campo Código De Área es obligatorio',
                'codigoDeArea.unique' => 'El campo Código De Área no puede repetirse.',
                'codigoDeArea.numeric' => 'El campo Código De Área debe ser numérico.',
                'codigoDeArea.min' => 'El campo Código De Área debe tener al menos 2 caracteres',
                'codigoDeArea.max' => 'El campo Código De Área debe tener 4 caracteres como máximo'
            ]
        );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CodigoDeArea  $codigoDeArea
     * @return \Illuminate\Http\Response
     */
    public function destroy(CodigoDeArea $codigoDeArea)
    {
        //
    }
}
