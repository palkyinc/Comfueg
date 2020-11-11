<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $codComfueg = strtoupper($request->input('cod_comfueg'));
        $modelo = strtoupper($request->input('modelo'));
        $productos = Producto::select("*")
            ->whereRaw("UPPER(cod_comfueg) LIKE (?)", ["%{$codComfueg}%"])
            ->whereRaw("UPPER(modelo) LIKE (?)", ["%{$modelo}%"])
            ->paginate(10);
        //$productos = Producto::paginate(10);
        return view('adminProductos', ['productos' => $productos, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarProducto', ['datos' => 'active']);        
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
        $Producto = new Producto;
        $Producto->marca = $request->input('marca');
        $Producto->modelo = $request->input('modelo');
        $Producto->cod_comfueg = $request->input('cod_comfueg');
        $Producto->descripcion = $request->input('descripcion');
        $Producto->save();
        $respuesta[] = 'Producto se creo correctamente';
        return redirect('/adminProductos')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        return view('modificarProducto', [
            'elemento' => $producto]);
    }

    public function validar(Request $request, $idProducto = "")
    {
        if ($idProducto) {
            $condicion = 'required|min:3|max:45|unique:equipos,mac_address,' . $idProducto;
        } else {
            $condicion = 'required|min:3|max:45|unique:equipos,mac_address,';
        }
        $request->validate(
            [
                'marca' => 'required|min:2|max:45',
                'modelo' => 'required|min:2|max:45',
                'cod_comfueg' => $condicion,
                'descripcion' => 'max:100'
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $marca = $request->input('marca');
        $modelo = $request->input('modelo');
        $cod_comfueg = $request->input('cod_comfueg');
        $descripcion = $request->input('descripcion');
        $Producto = Producto::find($request->input('id'));
        $this->validar($request, $Producto->id);
        $Producto->marca = $marca;
        $Producto->modelo = $modelo;
        $Producto->cod_comfueg = $cod_comfueg;
        $Producto->descripcion = $descripcion;
        $respuesta[] = 'Se cambió con exito:';
        if ($Producto->marca != $Producto->getOriginal()['marca']) {
            $respuesta[] = ' marca: ' . $Producto->getOriginal()['marca'] . ' POR ' . $Producto->marca;
        }
        if ($Producto->modelo != $Producto->getOriginal()['modelo']) {
            $respuesta[] = ' Modelo: ' . $Producto->getOriginal()['modelo'] . ' POR ' . $Producto->modelo;
        }
        if ($Producto->cod_comfueg != $Producto->getOriginal()['cod_comfueg']) {
            $respuesta[] = ' Cod_comfueg: ' . $Producto->getOriginal()['cod_comfueg'] . ' POR ' . $Producto->cod_comfueg;
        }
        if ($Producto->descripcion != $Producto->getOriginal()['descripcion']) {
            $respuesta[] = ' Descripción: ' . $Producto->getOriginal()['descripcion'] . ' POR ' . $Producto->descripcion;
        }
        $Producto->save();
        return redirect('adminProductos')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
