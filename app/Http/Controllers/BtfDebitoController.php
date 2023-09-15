<?php

namespace App\Http\Controllers;

use App\Models\Btf_debito;
use App\Models\Cliente;
use App\Models\Conceptos_debito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use File;

class BtfDebitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cliente = isset($request->codComfueg) ? $request->codComfueg : null;
        if (null !== ($request->rebusqueda)) {
            $habilitadas = isset($request->habilitadas) ? 'on' : 'off';
            $rebusqueda = true;
        } else {
            $habilitadas = 'on';
            $rebusqueda = null;
        }
        $btf_debitos = Btf_debito::habilitadas($habilitadas)
                                    ->cliente($cliente)
                                    ->orderBy('cliente_id', 'ASC')
                                    ->paginate(10);
        return view ('adminBtfDebitos', [
            'btf_debitos' => $btf_debitos,
            'controller' => 'active',
            'habilitadas' => $habilitadas,
            'codComfueg' => $cliente,
            'rebusqueda' => $rebusqueda
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
        } else {
            $sin_cliente_id = false;
        }
        $conceptos = Conceptos_debito::where('desactivado', false)->get();
        return view('agregarBtfDebito', [
            'sin_cliente_id' => $sin_cliente_id,
            'controller' => 'active',
            'conceptos' => $conceptos
        ]);
    }
    public function create_ext ($id) {
        $debito = Btf_debito::find($id);
        $conceptos = Conceptos_debito::where('desactivado', false)->get();
        return view ('agregarBtfDebito_ext', [
            'controller' => 'active',
            'conceptos' => $conceptos,
            'debito' => $debito
        ]);
    }
    public function createClienteId(Request $request)
    {
        $this->validar($request, true);
        $conceptos = Conceptos_debito::where('desactivado', false)->get();
        if($cliente = Cliente::find($request['cliente_id'])) {
            return view('/agregarBtfDebito', [
                'sin_cliente_id' => false,
                'cliente_id' => $cliente->id,
                'cliente_NomYApe' => $cliente->getNomYApe(true),
                'controller' => 'active',
                'conceptos' => $conceptos
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
                    'concepto_id' => 'required|numeric|digits:3',
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
                    'concepto_id.required' => 'Debe seleccionar un concepto.', 
                    'concepto_id.numeric' => 'Error el ID del concepto debe ser numerico.',
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
        //dd($request);
        $btf_debito = new Btf_debito;
        $btf_debito->importe = $request->importe1 . '.' . $request->importe2;
        $btf_debito->dni = $request->dni;
        $btf_debito->concepto_id = $request->concepto_id;
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
        $conceptos = Conceptos_debito::where('desactivado', false)->get();
        return view('modificarBtfDebito', [
                                            'debito' => $debito, 
                                            'controller' => 'active',
                                            'conceptos' => $conceptos
                                        ]);
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
        $btf_debito->concepto_id = $request->concepto_id;
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
        $cant_debitos = count($debitos);
        $tot_importe = Btf_debito::where('desactivado', false)->sum('importe');
        $dia = date('d');
        $mes = date('m');
        $anio = date('Y');
        $fecha_presentacion_full = $dia . ' de ' . $this->getMonth($mes) . ' de ' . $anio;
        $fecha_presentacion_short = $dia . '/' . $mes . '/' . $anio;
        $fecha_presentacion_txt = date('dmY');
        $fileName = 'DEBAUT-' . date('Ymd');
        
        ### Generacion de txt
        $newFile = fopen ('../storage/app/public/BTF/' . $fileName . '.txt', 'w');
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
                                $fecha_presentacion_txt .
                                '000000000000000000000' . 
                                PHP_EOL);
                                ### Agrega fecha de presentacion
                                $debito->fecha_presentacion = date('Y-m-d');
                                ### deshabilitar si es excepcional
                                if ($debito->excepcional) {
                                    $debito->desactivado = true;
                                }
                                $debito->save();
        }
        fclose($newFile); 
        
        ### Generar PDF
        $pdf = PDF::loadView('presBtf', [
                'debitos' => $debitos,
                'fecha_presentacion_full' => $fecha_presentacion_full,
                'fecha_presentacion_short' => $fecha_presentacion_short,
                'cant_debitos' => $cant_debitos,
                'mes_anio' => $this->getMonth($mes) . ' ' . $anio,
                'tot_importe' => '$' . number_format($tot_importe, 2, ',', '.')
            ]);
        $pdf->save('../storage/' . public_path('BTF/' . $fileName . '.pdf'));
        
        ### Zip Files
        $zip = new ZipArchive;
        if ($zip->open('../storage/' . public_path('/BTF/' . $fileName . '.zip'), ZipArchive::CREATE) === TRUE) {
            $zip->addFile('../storage/' . public_path('/BTF/' . $fileName . '.txt'), $fileName . '.txt');
            $zip->addFile('../storage/' . public_path('/BTF/' . $fileName . '.pdf'), $fileName . '.pdf');
            $zip->close();
            return Storage::disk('public')->download('/BTF/' . $fileName . '.zip');
        } else {
            return redirect('/adminBtfDebitos')->with('mensaje', ['Error al crear archivo *.zip']);
        }
        //Enviar email
    }
    private function getMonth ($mes) {
        switch ($mes) {
            case '01':
                return 'Enero';
                break;
            case '02':
                return 'Febrero';
                break;
            case '03':
                return 'Marzo';
                break;
            case '04':
                return 'Abril';
                break;
            case '05':
                return 'Mayo';
                break;
            case '06':
                return 'Junio';
                break;
            case '07':
                return 'Julio';
                break;
            case '08':
                return 'Agosto';
                break;
            case '09':
                return 'Septiembre';
                break;
            case '10':
                return 'Octubre';
                break;
            case '11':
                return 'Noviembre';
                break;
            case '12':
                return 'Diciembre';
                break;
            default:
                return 'Error';
                break;
        }
    }
}
