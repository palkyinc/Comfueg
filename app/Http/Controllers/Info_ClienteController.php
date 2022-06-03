<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Contadores_mensuales;
use App\Models\Equipo;
use App\Models\Proveedor;
use App\Models\Issue;
use App\Models\Site_has_incidente;
use App\Charts\TortaIssuesChart;

class Info_ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        foreach ($request->server as $key => $item) {
            if ($key === 'REMOTE_ADDR') {
                $ip_cliente = $item;
            }
        }
        $es_cliente = ( $equipo = Equipo::where('ip', $ip_cliente)->first() ) ? true : false;
        if ($es_cliente)
        {
            $contrato = Contrato::where('num_equipo', $equipo->id)->where('baja', false)->first();
            $conteo = Contadores_mensuales::where('contrato_id', $contrato->id)->first();
        } else {
            return redirect('inicio');
        }
        //dd($contrato);
        ## Si ip_cliente no existe como contrato de Alta
        ## Si ip_cliente esta habilitado OK mostrar pagina con info de navegación
        ## Si ip_cliente esta suspendido con instalacion pendiente mostrar pagina de instalación
        ## Si ip_cliente esta suspendido solamente mostrar mensaje de corte de servicio.
        return view ('inicio2', [ 'ip_cliente' => $ip_cliente,
                                'es_cliente' => $es_cliente,
                                'contrato' => $contrato,
                                'conteo' => $conteo
                                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $tickets['vencidos'] = 0;
        $tickets['no_vencidos'] = 0;
        $issues = Issue::where('asignado_id', auth()->user()->id)
                        ->where('closed', false)
                        ->get();
        $tickets['total'] = count($issues);
        foreach ($issues as $issue) {
            //dd($issue);
            if ($issue->getVencida(true)) {
                $tickets['vencidos'] ++;
            }else {
                $tickets['no_vencidos'] ++;
            }
        }
        $hoy = new DateTime();
        $hace_un_mes = $hoy->modify('-31 days');
        $hace_un_mes = $hoy->format('Y-m-d h:i:s');
        $total_issues = Issue::where('created_at', '>', $hace_un_mes)->get();
        $total_tickets['total'] = count($total_issues);
        $total_tickets['total_prom_dia'] = round( $total_tickets['total'] / 22, 2);
        $total_tickets['abiertos'] = 0;
        $total_tickets['abiertos_vencidos'] = 0;
        $total_tickets['finalizados_no_vencidos'] = 0;
        $total_tickets['tipos'] = [];
        foreach ($total_issues as $issue) {
            if (isset($total_tickets['tipos'][$issue->titulo_id])){
                    $total_tickets['tipos'][$issue->titulo_id] ++;
            }else {
                $total_tickets['tipos'][$issue->titulo_id] = 1;
            }
            if (!$issue->closed){
                $total_tickets['abiertos'] ++;
                if ($issue->getVencida(true)) {
                    $total_tickets['abiertos_vencidos'] ++;
                }
            } elseif (!$issue->getVencida(true)) {
                $total_tickets['finalizados_no_vencidos'] ++;
            }
        }
        ($total_tickets['tipos'] = json_encode($total_tickets['tipos']));
        $total_tickets['abiertos_porc'] = round($total_tickets['abiertos'] *100 / $total_tickets['total'], 2);
        $total_tickets['finalizados_no_vencidos_porc'] = 
        round ($total_tickets['finalizados_no_vencidos'] * 100 / ($total_tickets['total'] - $total_tickets['abiertos']));
        $total_tickets['abiertos_vencidos_porc'] = round($total_tickets['abiertos_vencidos'] *100 / $total_tickets['abiertos']);
        return view(  'inicio', [   'frase' => false,
                                    'tickets' => $tickets,
                                    'total_tickets' => $total_tickets,
                                    'proveedoresCaidos' => Proveedor::provedoresCaidos() , 
                                    'incidentes' => Site_has_incidente::incidentesAbiertos() ,
                                    'principal' => 'active']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
