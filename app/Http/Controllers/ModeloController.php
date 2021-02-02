<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Modelos = modelo::paginate(10);
        return view('adminModelos', ['modelos' => $Modelos, 'sistema' => 'active']);
        dd($Modelos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('agregarModelo', ['sistema'=> 'active']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|min:2|max:45']);
        $Modelo = new Modelo;
        $Modelo->nombre = $request->input('nombre');
        $Modelo->save();
        $respuesta[] = 'Modelo se creo correctamente';
        return redirect('/adminModelos')->with('mensaje', $respuesta);
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
        $Modelo = Modelo::find($id);
        return view('modificarModelo', ['elemento' => $Modelo, 'sistema' => 'active']);
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
        $nombre = $request->input('nombre');
        $modelo = Modelo::find($request->input('id'));
        $request->validate(['nombre' => 'required|min:2|max:45']);
        $modelo->nombre = $nombre;
        $respuesta[] = 'Se cambió con exito:';
        if ($modelo->nombre != $modelo->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $modelo->getOriginal()['nombre'] . ' POR ' . $modelo->nombre;
        }
        $modelo->save();
        return redirect('adminModelos')->with('mensaje', $respuesta);
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
}
