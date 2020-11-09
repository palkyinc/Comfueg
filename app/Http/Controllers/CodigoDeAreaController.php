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
        return view('agregarCodigoDeArea', ['datos' => 'active']);
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
        $CodigoDeArea = new CodigoDeArea;
        $CodigoDeArea->codigoDeArea = $request->input('codigoDeArea');
        $CodigoDeArea->provincia = $request->input('provincia');
        $CodigoDeArea->localidades = $request->input('localidades');
        $CodigoDeArea->save();
        $respuesta[] = 'Código De área se creo correctamente';
        return redirect('/adminCodigosDeArea')->with('mensaje', $respuesta);
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
        $this->validar($request, $codigoArea->id);
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
        return redirect('adminCodigosDeArea')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idCodigoDeArea = "")
    {
        if ($idCodigoDeArea) {
            $condicion = 'required|numeric|min:11|max:9999|unique:codigosDeArea,codigoDeArea,' . $idCodigoDeArea;
        } else {
            $condicion = 'required|numeric|min:11|max:9999|unique:codigosDeArea,codigoDeArea';
        }
        $request->validate(
            [
                'codigoDeArea' => $condicion,
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
