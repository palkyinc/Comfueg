<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Contadores_mensuales;
use App\Models\Equipo;

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
        //$es_cliente = false;
        //dd($equipo);
        if ($es_cliente)
        {
            $contrato = Contrato::where('num_equipo', $equipo->id)->where('baja', false)->first();
            $conteo = Contadores_mensuales::where('contrato_id', $contrato->id)->first();
        } else {
            $contrato = null;
            $conteo = null;
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
    public function create()
    {
        //
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
