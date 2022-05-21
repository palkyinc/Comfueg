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
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeApi(Request $request)
    {
        $this->storeInBase($request);
        return response()->json(true, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->storeInBase($request);
        $respuesta[] = 'Cliente se creo correctamente';
        return redirect('/adminClientes')->with('mensaje', $respuesta);
    }

    private function storeInBase (Request $request) {
        $this->validar($request);
        $cliente = new Cliente;
        $cliente->id = $request->input('id');
        $cliente->nombre = $this->primerasMayusculas(trim($request->input('nombre')));
        $cliente->apellido = strtoupper(trim($request->input('apellido')));
        $cliente->cod_area_tel = $request->input('cod_area_tel');
        $cliente->telefono = $request->input('telefono');
        $cliente->cod_area_cel = $request->input('cod_area_cel');
        $cliente->celular = $request->input('celular');
        $cliente->email = $request->input('email');
        $cliente->es_empresa = ($request->input('es_empresa') == 'true' || $request->input('es_empresa') == 'on') ? true : false;
        $cliente->save();
    }

    private function primerasMayusculas ($string) {
        $words = explode (' ', strtolower(trim($string)));
        $string = '';
        foreach ($words as $word) {
            if ($string != '') {
                $string = $string . ' ' . ucfirst($word);
            } else {
                $string = ucfirst($word);
            }
        }
        return $string;
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
     * Return the specified resource.
     *
     * @param  Id_Cliente
     * @return  JSON
     */
    public function search($id)
    {
        $cliente = Cliente::select('id','nombre', 'apellido','cod_area_tel', 'telefono', 'cod_area_cel', 'celular', 'email', 'es_empresa')->find($id);
        if ($cliente)
        {
            $cliente->cod_area_tel = CodigoDeArea::select('id', 'codigoDeArea', 'provincia')->find($cliente->cod_area_tel);
            $cliente->cod_area_cel = CodigoDeArea::select('id', 'codigoDeArea', 'provincia')->find($cliente->cod_area_cel);
        }
        return response()->json($cliente, 200);
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
        if ($request->es_empresa == 'true'){
            $celular = 'nullable|numeric|digits:' . $longCel;
        }else {
            $celular = 'required|numeric|digits:' . $longCel;
        }
        $aValidar = [
            'id' => 'required|numeric|min:1|max:99999',
            'nombre' => 'nullable|min:2|max:45',
            'apellido' => 'required|min:2|max:45',
            'cod_area_tel' => 'required',
            'cod_area_cel' => 'required',
            'celular' => $celular,
            'telefono' => 'nullable|numeric|digits:' . $longTel,
            'email' => 'nullable|email:rfc,dns'
        ];
        $request->validate($aValidar);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateApi(Request $request)
    {
        $this->updateInBase($request, false);
        return response()->json(true, 200);
    }

    private function updateInBase( Request $request, $noEsApi)
    {
        $nombre = $this->primerasMayusculas(trim($request->input('nombre')));
        $apellido = strtoupper(trim($request->input('apellido')));
        $cod_area_tel = $request->input('cod_area_tel');
        $telefono = $request->input('telefono');
        $cod_area_cel = $request->input('cod_area_cel');
        $celular = $request->input('celular');
        $email = $request->input('email');
        $cliente = Cliente::find($request->input('id'));
        $this->validar($request);
        $cliente->nombre = $nombre;
        $cliente->apellido = $apellido;
        $cliente->cod_area_tel = $cod_area_tel;
        $cliente->telefono = $telefono;
        $cliente->cod_area_cel = $cod_area_cel;
        $cliente->celular = $celular;
        $cliente->email = $email;
        $cliente->es_empresa = ($request->input('es_empresa') == 'true' || $request->input('es_empresa') == 'on') ? true : false;
        if ($noEsApi)
        {
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
            if ($cliente->es_empresa != $cliente->getOriginal()['es_empresa']) {
                $respuesta[] = ' Empresa: ' . $cliente->getOriginal()['es_empresa'] . ' POR ' . $cliente->es_empresa;
            }
            $cliente->save();
            return $respuesta;
        }
        $cliente->save();
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
        return redirect('adminClientes')->with('mensaje', $this->updateInBase($request, true));
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
