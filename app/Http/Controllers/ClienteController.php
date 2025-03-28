<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CodigoDeArea;
use Illuminate\Http\Request;
use App\Models\Conceptos_debito;
use Illuminate\Support\Facades\Auth;

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
        return view('adminClientes', [
            'clientes' => $clientes, 
            'datos' => 'active',
            'num_cliente' => $num_cliente,
            'apellido' => $apellido]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( null !==  session('btf_debito') )
        {
            $datos = 'controller';
        } else {
            $datos = 'datos';
        }
        $codigosArea = CodigoDeArea::all();
        return view('agregarCliente', ['codigosArea' =>$codigosArea, $datos => 'active']);
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
        if (isset($request->btf_debito)) {
            $cliente = Cliente::find($request->id);
            $conceptos = Conceptos_debito::where('desactivado', false)->get();
            return view('/agregarBtfDebito', [
                'sin_cliente_id' => false,
                'cliente_id' => $cliente->id,
                'cliente_NomYApe' => $cliente->getNomYApe(true),
                'controller' => 'active',
                'conceptos' => $conceptos
        ]);
        } else {
            $respuesta[] = 'Cliente se creo correctamente';
            return redirect('/adminClientes')->with('mensaje', $respuesta);
        }
        
    }

    private function storeInBase (Request $request) {
        $this->validar($request);
        $cliente = new Cliente;
        $cliente->es_empresa = ($request->input('es_empresa') == 'true' || $request->input('es_empresa') == 'on') ? true : false;
        if ($cliente->es_empresa && isset($request->razonSocial)) {
            $cliente->apellido = strtoupper(trim($request->input('razonSocial')));
        } else {
            $cliente->apellido = strtoupper(trim($request->input('apellido')));
        }
        $cliente->id = $request->input('id');
        $cliente->nombre = $this->primerasMayusculas(trim($request->input('nombre')));
        $cliente->cod_area_tel = $request->input('cod_area_tel');
        $cliente->telefono = $request->input('telefono');
        $cliente->cod_area_cel = $request->input('cod_area_cel');
        $cliente->celular = $request->input('celular');
        $cliente->email = $request->input('email');
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

    public function validar(Request $request, $modificando = false)
    {
        if ($modificando){
            $id = 'required|numeric|min:1|max:99999';
        }else {
            $id = 'required|numeric|min:1|max:99999|unique:clientes,id';
        }
        if ($request->cod_area_tel && $request->cod_area_cel)
        {
            $longTel = 10-strlen(CodigoDeArea::find($request->cod_area_tel)->codigoDeArea);
            $longCel = 10-strlen(CodigoDeArea::find($request->cod_area_cel)->codigoDeArea);
        } else {
            $longTel = 20;
            $longCel = 20;
        }
        if ($request->es_empresa == 'on' || $request->es_empresa == 'true'){
            $celular = 'nullable|numeric|digits:' . $longCel;
            $nombre = 'nullable|min:2|max:45';
            if(isset($request->razonSocial)) {
                $razonSocial = 'required|min:2|max:45';
                $apellido = 'nullable|min:2|max:45';
            } else {
                $apellido = 'required|min:2|max:45';
                $razonSocial = 'nullable|min:2|max:45';
            }
        }else {
            $nombre = 'required|min:2|max:45';
            $apellido = 'required|min:2|max:45';
            $razonSocial = 'nullable|min:2|max:45';
            $celular = 'required|numeric|digits:' . $longCel;
        }
        $aValidar = [
            'id' => $id,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'razonSocial' => $razonSocial,
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
        $this->validar($request, true);
        $cliente = Cliente::find($request->input('id'));
        $cliente->nombre = $this->primerasMayusculas(trim($request->input('nombre')));
        $cliente->apellido = strtoupper(trim($request->input('apellido')));
        $cliente->cod_area_tel = $request->input('cod_area_tel');
        $cliente->telefono = $request->input('telefono');
        $cliente->cod_area_cel = $request->input('cod_area_cel');
        $cliente->celular = $request->input('celular');
        $cliente->email = $request->input('email');
        $cliente->es_empresa = ($request->input('es_empresa') == 'true' || $request->input('es_empresa') == 'on') ? 1 : 0;
        if ($noEsApi)
        {
            if ($cliente->isDirty()) {
                $respuesta['info'][] = 'Cambios en el cliente: '. $cliente->getNomYApe() . '-> ';
                if ($cliente->isDirty('nombre')) {
                    $respuesta['success'][] = ' Nombre: ' . $this->esVacio($cliente->getOriginal()['nombre']) . ' POR ' . $this->esVacio($cliente->nombre);
                }
                if ($cliente->isDirty('apellido')) {
                    $respuesta['success'][] = ' Apellido: ' . $this->esVacio($cliente->getOriginal()['apellido']) . ' POR ' . $this->esVacio($cliente->apellido);
                }
                if ($cliente->isDirty('cod_area_tel')) {
                    $respuesta['success'][] = ' Código área Teléfono: ' . $this->esVacio($cliente->getOriginal()['cod_area_tel']) . ' POR ' . $this->esVacio($cliente->cod_area_tel);
                }
                if ($cliente->isDirty('telefono')) {
                    $respuesta['success'][] = ' Teléfono: ' . $this->esVacio($cliente->getOriginal()['telefono']) . ' POR ' . $this->esVacio($cliente->telefono);
                }
                if ($cliente->isDirty('cod_area_cel')) {
                    $respuesta['success'][] = ' Código área Celular: ' . $this->esVacio($cliente->getOriginal()['cod_area_cel']) . ' POR ' . $this->esVacio($cliente->cod_area_cel);
                }
                if ($cliente->isDirty('celular')) {
                    $respuesta['success'][] = ' Celular: ' . $this->esVacio($cliente->getOriginal()['celular']) . ' POR ' . $this->esVacio($cliente->celular);
                }
                if ($cliente->isDirty('email')) {
                    $respuesta['success'][] = ' email: ' . $this->esVacio($cliente->getOriginal()['email']) . ' POR ' . $this->esVacio($cliente->email);
                }
                if ($cliente->isDirty('es_empresa')) {
                    $respuesta['success'][] = ' Empresa: ' . $this->esVacio($cliente->getOriginal()['es_empresa']) . ' POR ' . $this->esVacio($cliente->es_empresa);
                }
            } else {
                $respuesta['warning'][] = 'No hubo cambios en el cliente: '. $cliente->getNomYApe() . ': ';
            }
            $cliente->save();
            if($contratos = \App\Models\Contrato::where('num_cliente', $cliente->id)->get())
            {
                $descripcion = null;
                foreach ($respuesta as $key => $value) {
                    $descripcion .= implode(". ", $value);
                }
                foreach ($contratos as $key => $contrato) {
                    $ticket = \App\Models\Issue::create([
                        'titulo_id' => 9,
                        'descripcion' => $descripcion,
                        'asignado_id' => Auth::id(),
                        'creator_id' => 1,
                        'cliente_id' => $cliente->id,
                        'contrato_id' => $contrato->id,
                        'closed' => true]);
                    $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambio en titular del contrato N°: ' . $contrato->id;
                }
            }
            ### SI tiene $cliente pertenece a algun $contratos entonces crear tickets con $respuesta
            return $respuesta;
        }
        $cliente->save();
    }

    private function esVacio ( $cadena)
    {
        return $cadena ? $cadena : 'null';
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
        return redirect('inicio')->with('mensaje', $this->updateInBase($request, true));
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
