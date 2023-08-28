<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conceptos_debito;

class Concepto_debitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conceptos = Conceptos_debito::select()->paginate(10);
        return view ('adminConceptoDebitos', [
            'conceptos' => $conceptos,
            'controller' => 'active'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view ('agregarConceptoDebito', [
                'controller' => 'active'
            ]);
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
        $concepto = new Conceptos_debito;
        $concepto->id = $request->cod_concepto;
        $concepto->concepto = $request->descripcion;
        $concepto->desactivado = false;
        //$concepto->save();
        $respuesta['success'][] = 'Se creó Concepto N° ' . $concepto->id . ' correctamente';
        return redirect('/adminConceptoDebitos')->with('mensaje', $respuesta);
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
        $concepto = Conceptos_debito::find($id);
        return view ('modificarConceptoDebitos', ['concepto' => $concepto, 'controller' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validar($request, true);
        $concepto = Conceptos_debito::find($request->cod_concepto);
        $concepto->concepto = $request->descripcion;
        if ($concepto->concepto === $concepto->getOriginal()['concepto']) {
            $respuesta['error'][] = 'No hay cambios en la descripción';
        } else {
            $concepto->save();
            $respuesta['success'][] = 'Se cambió correctamente la descripción del Concepto N° ' . $concepto->id .
                                        ' de: ' . $concepto->getOriginal()['concepto'] . 
                                        '; a: ' . $concepto->concepto;
        }
        
        return redirect('/adminConceptoDebitos')->with('mensaje', $respuesta);
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
    public function enable(Request $request)
    {
        $concepto = Conceptos_debito::find($request->id);
        $concepto->desactivado = false;
        $concepto->save();
        $respuesta['success'][] = 'Se habilitó Concepto N° ' . $concepto->id . ' correctamente';
        return redirect('/adminConceptoDebitos')->with('mensaje', $respuesta);
    }
    public function unable(Request $request)
    {
        dd('Falta chequear si el concepto esta en algún debito');
        $concepto = Conceptos_debito::find($request->id);
        $concepto->desactivado = true;
        $concepto->save();
        $respuesta['success'][] = 'Se deshabilitó Concepto N° ' . $concepto->id . ' correctamente';
        return redirect('/adminConceptoDebitos')->with('mensaje', $respuesta);
    }
    private function validar(Request $request, $edit = false)
    {
        if ($edit) {
            $cod_concepto = 'required|numeric';
        } else {
            $cod_concepto = 'required|numeric|unique:conceptos_debitos,id';
        }
        
        $request->validate(
            [
                'descripcion' => 'required|min:2|max:30',
                'cod_concepto' => $cod_concepto
            ],
            [
                'descripcion.required' => 'El campo Descripción es obligatorio',
                'descripcion.min' => 'El campo Descripción debe tener al menos 2 caractéres',
                'descripcion.max' => 'El campo Descripción debe tener 30 caractéres como máximo',
                'cod_concepto.required' => 'El campo Código Concepto es obligatorio',
                'cod_concepto.unique' => 'El campo Código Concepto no puede repetirse.',
                'cod_concepto.numeric' => 'El campo Código Concepto debe ser un número',
            ]
        );
    }
}
