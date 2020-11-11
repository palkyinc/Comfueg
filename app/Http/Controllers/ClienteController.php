<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CodigoDeArea;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $num_cliente = strtoupper($request->input('num_cliente'));
        $apellido = strtoupper($request->input('apellido'));
        $clientes = Cliente::select("*")
            ->whereRaw("UPPER(id) LIKE (?)", ["%{$num_cliente}%"])
            ->whereRaw("UPPER(apellido) LIKE (?)", ["%{$apellido}%"])
            ->paginate(10);
        return view('adminClientes', ['clientes' => $clientes, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $codigosArea = CodigoDeArea::all();
        return view('agregarCliente', ['codigosArea' =>$codigosArea, 'datos' => 'active']);
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
        $Cliente = new Cliente;
        $Cliente->id = $request->input('id');
        $Cliente->nombre = ucfirst(strtolower($request->input('nombre')));
        $Cliente->apellido = strtoupper($request->input('apellido'));
        $Cliente->cod_area_tel = $request->input('cod_area_tel');
        $Cliente->telefono = $request->input('telefono');
        $Cliente->cod_area_cel = $request->input('cod_area_cel');
        $Cliente->celular = $request->input('celular');
        $Cliente->email = $request->input('email');
        $Cliente->save();
        $respuesta[] = 'Cliente se creo correctamente';
        return redirect('/adminClientes')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $codigosArea = CodigoDeArea::all();
        $Cliente = Cliente::find($id);
        return view('modificarCliente', ['elemento' => $Cliente, 'codigosArea' =>$codigosArea, 'datos' => 'active']);
    }

    public function validar(Request $request)
    {
        //dd(CodigoDeArea::find($request->cod_area_tel)->codigoDeArea);
        if ($request->cod_area_tel && $request->cod_area_cel)
        {
            $longTel = 10-strlen(CodigoDeArea::find($request->cod_area_tel)->codigoDeArea);
            $longCel = 10-strlen(CodigoDeArea::find($request->cod_area_cel)->codigoDeArea);
        } else {
            $longTel = 20;
            $longCel = 20;
        }
        $aValidar = [
            'id' => 'required|numeric|min:1|max:99999',
            'nombre' => 'nullable|min:2|max:45',
            'apellido' => 'required|min:2|max:45',
            'cod_area_tel' => 'required',
            'cod_area_cel' => 'required',
            'celular' => 'required|numeric|digits:' . $longCel,
            'telefono' => 'nullable|numeric|digits:' . $longTel,
            'email' => 'nullable|email:rfc,dns'
        ];
        $request->validate($aValidar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $nombre = $request->input('nombre');
        $apellido = $request->input('apellido');
        $cod_area_tel = $request->input('cod_area_tel');
        $telefono = $request->input('telefono');
        $cod_area_cel = $request->input('cod_area_cel');
        $celular = $request->input('celular');
        $email = $request->input('email');
        $cliente = Cliente::find($request->input('id'));
        $this->validar($request, $cliente);
        $cliente->nombre = $nombre;
        $cliente->apellido = $apellido;
        $cliente->cod_area_tel = $cod_area_tel;
        $cliente->telefono = $telefono;
        $cliente->cod_area_cel = $cod_area_cel;
        $cliente->celular = $celular;
        $cliente->email = $email;
        $respuesta[] = 'Se cambió con exito:';
        if ($cliente->nombre != $cliente->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $cliente->getOriginal()['nombre'] . ' POR ' . $cliente->nombre;
        }
        if ($cliente->apellido != $cliente->getOriginal()['apellido']) {
            $respuesta[] = ' Apellido: ' . $cliente->getOriginal()['apellido'] . ' POR ' . $cliente->apellido;
        }
        if ($cliente->cod_area_tel != $cliente->getOriginal()['cod_area_tel']) {
            $respuesta[] = ' Código área Teléfono: ' . $cliente->getOriginal()['cod_area_tel'] . ' POR ' . $cliente->cod_area_tel;
        }
        if ($cliente->telefono != $cliente->getOriginal()['telefono']) {
            $respuesta[] = ' Teléfono: ' . $cliente->getOriginal()['telefono'] . ' POR ' . $cliente->telefono;
        }
        if ($cliente->cod_area_cel != $cliente->getOriginal()['cod_area_cel']) {
            $respuesta[] = ' Código área Celular: ' . $cliente->getOriginal()['cod_area_cel'] . ' POR ' . $cliente->cod_area_cel;
        }
        if ($cliente->celular != $cliente->getOriginal()['celular']) {
            $respuesta[] = ' Celular: ' . $cliente->getOriginal()['celular'] . ' POR ' . $cliente->celular;
        }
        if ($cliente->email != $cliente->getOriginal()['email']) {
            $respuesta[] = ' email: ' . $cliente->getOriginal()['email'] . ' POR ' . $cliente->email;
        }
        $cliente->save();
        return redirect('adminClientes')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
