<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Issue;
use App\Models\Issues_update;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Issue_title;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuarios = User::get();
        $userSelected = (isset($request->usuario)) ? (($request->usuario != 'todos') ? $request->usuario : null) : auth()->user()->id;
        $abiertas = isset($request->abiertas) ? 'on' : (isset($request->rebusqueda ) ? 'off' : 'on' );
        $cliente = isset($request->cliente) ? $request->cliente : null;
        $contrato = isset($request->contrato) ? $request->contrato : null;
        $incidentes = Issue::asignado($userSelected)
                        ->abierta($abiertas)
                        ->cliente($cliente)
                        ->contrato($contrato)
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
        return view('adminIssues',    [ 'incidentes' => $incidentes, 
                                        'userSelected' => $userSelected, 
                                        'usuarios' => $usuarios, 
                                        'cliente' => $cliente, 
                                        'abiertas' => $abiertas, 
                                        'contrato' => $contrato, 
                                        'internet' => 'active']);
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
            $cliente = $contrato->relCliente;
            $contratos = Contrato::where('num_cliente', $cliente->id)->get();
        }else {
            $contrato=null;
        }
        $titulos = Issue_title::get();
        $usuarios = User::get();
        return view ('agregarIssue', [
                                        'internet' => 'active',
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
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $viewers = $this->getViewers($request, 5);
        $this->validar($request);
        if ($rta = $this->validarTwo($request)) {
            return back()->withInput()->withErrors(['msg' => [$rta]]);
        }
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
        $issue->enviarMail(1);
        $respuesta[] = 'Nuevo Ticket se ha creado correctamente';
        return redirect('/adminIssues')->with('mensaje', $respuesta);
    }

    public function validarTwo(Request $request) {
        $contrato = Contrato::find($request->afectado);
        $contract = isset($contrato->id) ? $contrato->id : null;
        // SI contrato dado de baja y (titulo distinto a "reconexion de contrato" y "Mudanza") ENTONCES mensaje:
        if (isset($contrato->baja) && $contrato->baja === 1 && !($request->titulo === "7" || $request->titulo === "8")){
            return 'Para los contratos dados de baja solo se pueden usar los Titulos: "Reconexión de contrato" o "Mudanza"';
        }
        // SINO SI contrato NO dado de baja y (titulo igual a "reconexion de contrato" o "Wispro") ENTONCES mensaje:
        if (isset($contrato->baja) && $contrato->baja === 0 && ($request->titulo === "7" || $request->titulo === "10") ){
            return 'No se puede usar los Títulos: "Reconexión de contrato" o "Wispro" en contratos activos (No Dados de baja).';
        }
        // SINO SI SIN contrato y (titulo distinto a "Wispro") ENTONCES mensaje:
        if (!isset($contrato->baja) && $request->titulo !== "10") {
            return ('Incidentes sin Contrato solo se puede usar en tickets con el Título "Wispro"');
        }
        // SINO SI existe issue con el mismo contrato y titulo mensaje:
        if (
            ($request->titulo === "2" || $request->titulo === "3") &&
            (Issue::where('closed', false)->where('titulo_id', "2")->where('contrato_id', $contract)->first() ||
            Issue::where('closed', false)->where('titulo_id', "3")->where('contrato_id', $contract)->first())
            ) {
                return 'Existe un Ticket para este contrato con este Título "Sin Conectividad" o "Problemas de Conectividad". Se debe actualizar el ticket existente sin duplicar el registro de incidentes';
        }
        if (Issue::where('closed', false)->where('titulo_id', $request->titulo)->where('contrato_id', $contract)->first()) {
            return 'Existe un Ticket para este contrato con este Título. Se debe actualizar el ticket existente sin duplicar el registro de incidentes';
        }
        return false;
    }

    public function getViewers(Request $request, $cant)
    {
        $viewers = null;
        if(count($request->request) > $cant)
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
        return $viewers;
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
    public function validarUpdate(Request $request)
    {
        $aValidar = [
            'contrato' => 'nullable|numeric',
            'asignado' => 'required|numeric',
            'actualizacion' => 'required|min:3|max:500'
        ];
        $request->validate($aValidar);
    }

    public function buscarCliente(Request $request)
    {
        $apellido = strtoupper($request->input('cliente'));
        $clientes = Cliente::select("*")
            ->whereRaw("UPPER(apellido) LIKE (?)", ["%{$apellido}%"])
            ->get();
        if (count($clientes) > 0)
        {
            return view ('agregarIssue2', ['internet' => 'active', 'clientes' => $clientes]);
        }
        else {
            return redirect('agregarIssue')->with('mensaje', ['No se encontraron coincidencias.']);
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
        $issue = Issue::find($id);
        $usuarios = User::get();
        $contratos = Contrato::where('num_cliente', $issue->cliente_id)->get();
        $issue_updates = Issues_update::where('issue_id', $id)->get();
        return view ('modificarIssue', ['internet' => 'active',
                                        'issue' => $issue,
                                        'contratos' => $contratos,
                                        'issues_updates' => $issue_updates,
                                        'usuarios' => $usuarios]);
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
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $this->validarUpdate($request);
        if (isset($request->closed))
        {
            $tot_request = 7;
        }
        else    {
            $tot_request = 6;
        }
        $viewers = json_encode($this->getViewers($request, $tot_request));
        $issue = Issue::find($request->id);
        $issue_updates = new Issues_update();
        $issue_updates->issue_id = $request->id;
        $issue_updates->descripcion = $request->actualizacion;
        $issue_updates->usuario_id = auth()->user()->id;
        $issue_updates->asignadoAnt_id = $issue->asignado_id;
        $issue_updates->asignadoSig_id = $request->asignado;
        $issue_updates->save();
        $guardar = false;
        $mailTipo = 2;
        if ($issue->asignado_id != $request->asignado)
        {
            $guardar = true;
            $issue->asignado_id = $request->asignado;
        }
        //$viewers = $this->getViewers($request, 6);
        if ($issue->contrato_id != $request->contrato)
        {
            $guardar = true;
            $issue->contrato_id = $request->contrato;
        }
        if ($issue->viewers != $viewers)
        {
            $guardar = true;
            $issue->viewers = $viewers;
        }
        if (isset($request->closed))
        {
            $guardar = true;
            $issue->closed = true;
            $mailTipo = 3;
        }
        if ($guardar)
        {
           $issue->save(); 
        }
        $issue->enviarMail($mailTipo);
        return redirect('adminIssues')->with('mensaje', ['Ticket actualizado correctamente.']);
    }

    public function getListadoIssues ()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $issues = Issue::orderByDesc('created_at')->get();
        $newFile = fopen ('../storage/app/public/ListadoIssues-' . date('Ymd') . '.csv', 'w');
        fwrite($newFile ,'#Ticket;Abierto;Titulo;Creador;Cliente;Cerrado;Panel;Descripcion' . PHP_EOL);
        foreach ($issues as $key => $value)
        {
            fwrite($newFile , 
                    $value->id . ';' .
                    $value->created_at . ';' . 
                    $value->relTitle->title . ';' .
                    $value->relCreator->name . ';' .
                    $value->relCliente->getNomYApe() . ';' .
                    ($value->closed ? 'CERRADA' : 'Abierta') . ';' .
                    ($value->relContrato->relPanel->ssid ?? 'N/A') . ';' .
                    (str_replace(array("\n", "\r", ";"), '|', $value->descripcion)) . ';' .
                    PHP_EOL);
        }
        fclose($newFile);
        return Storage::disk('public')->download('ListadoIssues-' . date('Ymd') . '.csv');
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
