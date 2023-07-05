<?php

namespace App\Http\Controllers;

use App\Models\Btf_debito;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BtfDebitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $habilitadas = isset($request->habilitadas) ? 'on' : 'off';
        $cliente = isset($request->codComfueg) ? $request->codComfueg : null;
        if (null !== ($request->rebusqueda)) {
            //dd($request);
        }
        $btf_debitos = Btf_debito::habilitadas($habilitadas)
                                    ->cliente($cliente)
                                    ->orderBy('cliente_id', 'ASC')
                                    ->paginate(10);
        return view ('adminBtfDebitos', [
            'btf_debitos' => $btf_debitos,
            'controller' => 'active',
            'habilitadas' => $habilitadas,
            'codComfueg' => $cliente
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!isset($request['cliente_id'])) {
            $sin_cliente_id = true;
        }
        return view('agregarBtfDebito', [
            'sin_cliente_id' => $sin_cliente_id,
            'controller' => 'active'
        ]);
    }
    public function createClienteId(Request $request)
    {
        $this->validar($request, true);
        if($cliente = Cliente::find($request['cliente_id'])) {
            return view('/agregarBtfDebito', [
                'sin_cliente_id' => false,
                'cliente_id' => $cliente->id,
                'cliente_NomYApe' => $cliente->getNomYApe(true),
                'controller' => 'active'
            ]);
        }else {
            return redirect('/agregarCliente')->with('btf_debito', $request['cliente_id'] );
        }
    }

    /* 
    $esClienteId -> false si es para validar los datos 
     */
    public function validar (Request $request, $esClienteId = false)
    {
        if ($esClienteId) {
            $request->validate (
                ['cliente_id' => 'required|numeric'],
                [
                    'cliente_id.required' => 'Genesys ID no puede estar vacío.', 
                    'cliente_id.numeric' => 'Genesys ID debe ser un número.'
                ] 
            );
        } else {
            $request->validate (
                [
                    'cliente_id' => 'required|numeric',
                    'importe1' => 'required|numeric|max:99999999999',
                    'importe2' => 'required|numeric|max:99',
                    'dni' => 'required|numeric|max:99999999',
                    'cuenta' => 'required|numeric|digits:9',
                    'tipo_cuenta' => 'required|numeric|digits:2',
                    'sucursal' => 'required|numeric|digits:2'
                ],
                [
                    'cliente_id.required' => 'Genesys ID no puede estar vacío.', 
                    'cliente_id.numeric' => 'Genesys ID debe ser un número.',
                    'importe1.required' => 'Importe no puede estar vacío.', 
                    'importe1.numeric' => 'Importe debe ser un número.',
                    'importe1.max' => 'Importe debe ser de 11 números.',
                    'importe2.required' => 'Los centavos del importe no pueden estar vacío.', 
                    'importe2.numeric' => 'Los centavos del importe deben ser un número.',
                    'importe2.max' => 'Los centavos del importe debe ser de 2 números.',
                    'dni.required' => 'DNI no puede estar vacío.', 
                    'dni.numeric' => 'DNI debe ser un número.',
                    'dni.max' => 'DNI debe ser de 8 números.',
                    'cuenta.required' => 'Cuenta no puede estar vacío.', 
                    'cuenta.numeric' => 'Cuenta debe ser un número.',
                    'cuenta.digits' => 'Cuenta debe ser de 9 dígitos.',
                    'tipo_cuenta.required' => 'Tipo de Cuenta no puede estar vacío.', 
                    'tipo_cuenta.numeric' => 'Tipo de Cuenta debe ser un número.',
                    'tipo_cuenta.digits' => 'Tipo de Cuenta debe ser de 2 dígitos.',
                    'sucursal.required' => 'Sucursal no puede estar vacío.', 
                    'sucursal.numeric' => 'Sucursal debe ser un número.',
                    'sucursal.digits' => 'Sucursal debe ser de 2 dígitos.',
                ] 
            );
        }
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
        $btf_debito = new Btf_debito;
        $btf_debito->importe = $request->importe1 . '.' . $request->importe2;
        $btf_debito->dni = $request->dni;
        $btf_debito->cliente_id = $request->cliente_id;
        $btf_debito->cuenta = $request->cuenta;
        $btf_debito->tipo_cuenta = $request->tipo_cuenta;
        $btf_debito->sucursal = $request->sucursal;
        if (isset($request->excepcional)) {
            $btf_debito->excepcional = true;
        }else {
            $btf_debito->excepcional = false;
        }
        $btf_debito->desactivado = false;
        $btf_debito->save();
        return redirect('/adminBtfDebitos')->with('mensaje', ['Nuevo debito para ' . $request->NomYApe . ' Creado con éxito!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Btf_debito  $btf_debito
     * @return \Illuminate\Http\Response
     */
    public function show(Btf_debito $btf_debito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Btf_debito  $btf_debito
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $debito = Btf_debito::find($id);
        return view('modificarBtfDebito', ['debito' => $debito, 'controller' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Btf_debito  $btf_debito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validar($request);
        $btf_debito = Btf_debito::find($request->debito_id);
        $btf_debito->importe = $request->importe1 . '.' . $request->importe2;
        $btf_debito->dni = $request->dni;
        $btf_debito->cliente_id = $request->cliente_id;
        $btf_debito->cuenta = $request->cuenta;
        $btf_debito->tipo_cuenta = $request->tipo_cuenta;
        $btf_debito->sucursal = $request->sucursal;
        $btf_debito->save();
        return redirect('/adminBtfDebitos')->with('mensaje', ['Débito de ' . $btf_debito->relCliente->getNomYApe() . ' Modificado!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Btf_debito  $btf_debito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Btf_debito $btf_debito)
    {
        //
    }
    public function enable(Request $request)
    {
        $btf_debito = Btf_debito::find($request->id);
        $btf_debito->desactivado = false;
        $btf_debito->save();
        return redirect('/adminBtfDebitos')->with('mensaje', ['Débito de ' . $btf_debito->relCliente->getNomYApe() . ' Habilitado!']);
    }
    public function disable(Request $request)
    {
        $btf_debito = Btf_debito::find($request->id);
        $btf_debito->desactivado = true;
        $btf_debito->save();
        return redirect('/adminBtfDebitos')->with('mensaje', ['Débito de ' . $btf_debito->relCliente->getNomYApe() . ' Deshabilitado!']);
    }
    public function getPresentacion ()
    {
        $debitos = Btf_debito::where('desactivado', false)->get();
        $fecha_presentación = date('dmY');
        //dd($fecha_presentación);
        $newFile = fopen ('../storage/app/public/DEBAUT-' . date('Ymd') . '.txt', 'w');
        foreach ($debitos as $key => $debito)
        {
            fwrite($newFile ,   
                                str_pad($debito->dni, 8, '0', STR_PAD_LEFT) . 
                                $debito->tipo_cuenta .
                                '00' .  
                                $debito->sucursal . 
                                $debito->cuenta .
                                '0000000000000000' . 
                                str_pad($debito->relCliente->id, 20, '0', STR_PAD_LEFT) . // 20 caracteres
                                '0000000000000000' .
                                '100000' . 
                                str_pad($debito->getImporte() . $debito->getImporte(true), 13, '0', STR_PAD_LEFT) . //13 caracteres
                                '268' .
                                '000000' .
                                $fecha_presentación .
                                '000000000000000000000' . 
                                PHP_EOL);
        }
        fclose($newFile);
        return Storage::disk('public')->download('DEBAUT-' . date('Ymd') . '.txt');
    }
}
