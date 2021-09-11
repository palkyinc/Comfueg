<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Issue;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Issue_title;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::get();
        $userSelected = null;
        $abiertas = true;
        $incidentes = Issue::paginate(10);
        //dd($usuarios);
        return view('adminIssues',    [ 'incidentes' => $incidentes, 
                                        'userSelected' => $userSelected, 
                                        'usuarios' => $usuarios , 
                                        'abiertas' => $abiertas, 
                                        'internet' => 'activate']);
        
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (isset($request->cliente_id))
        {
            $cliente = Cliente::find($request->cliente_id);
            $contratos = Contrato::where('num_cliente', $request->cliente_id)->get();
        }else {
            $cliente = null;
            $contratos = null;
        }
        if (isset($request->contrato_id))
        {
            $contrato = Contrato::find($request->contrato_id);
        }else {
            $contrato=null;
        }
        $titulos = Issue_title::get();
        $usuarios = User::get();
        return view ('agregarIssue', [
                                        'internet' => 'activate',
                                        'titulos' => $titulos,
                                        'contrato' => $contrato,
                                        'contratos' => $contratos,
                                        'usuarios' => $usuarios,
                                        'cliente' => $cliente
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
        $viewers = null;
        if(count($request->request) > 5)
        {
            $observadores = count(User::get());
            for ($i=1; $i < $observadores + 1; $i++) 
            { 
                $dato = 'viewer' . $i;
                if (isset($request[$dato]))
                {
                    if($viewers)
                    {
                        $viewers[] += $i;
                    }else   {
                                $viewers[] = $i;
                            }
                }
            }
        }
        $this->validar($request);
        $issue = new Issue();
        $issue->titulo_id = $request->titulo;
        $issue->descripcion = $request->descripcion;
        $issue->asignado_id = $request->asignado;
        $issue->creator_id = auth()->user()->id;
        $issue->cliente_id = $request->cliente_id;
        $issue->contrato_id = $request->afectado;
        $issue->viewers = json_encode($viewers);
        $issue->closed = false;
        $issue->save();
        $respuesta[] = 'Nuevo Ticket se ha creado correctamente';
        return redirect('/adminIssues')->with('mensaje', $respuesta);
    }

    public function validar(Request $request)
    {
        $aValidar = [
            'afectado' => 'nullable|numeric',
            'cliente_id' => 'required|numeric',
            'titulo' => 'required|numeric',
            'asignado' => 'required|numeric',
            'descripcion' => 'required|min:3|max:500'
        ];
        $request->validate($aValidar);
    }

    public function buscarCliente(Request $request)
    {
        $apellido = strtoupper($request->input('cliente'));
        $clientes = Cliente::select("*")
            ->whereRaw("UPPER(apellido) LIKE (?)", ["%{$apellido}%"])
            ->get();
        if (count($clientes) > 1)
        {
            return view ('agregarIssue2', ['internet' => 'activate', 'clientes' => $clientes]);
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
