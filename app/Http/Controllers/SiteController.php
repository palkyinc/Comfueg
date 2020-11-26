<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = strtoupper($request->input('nombre'));
        $sites = Site::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$nombre}%"])
            ->paginate(10);
        //$sites = Site::paginate(10);
        return view('adminSites', ['sites' => $sites, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarSite', ['datos' => 'active']);
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
        $Site = new Site;
        $Site->nombre = $request->input('nombre');
        $Site->descripcion = $request->input('descripcion');
        $Site->coordenadas = $request->input('coordenadas');
        $Site->rangoIp = $request->input('rangoIp');
        $Site->ipDisponible = $request->input('ipDisponible');
        $Site->distancia = $request->input('distancia');
        $Site->save();
        $respuesta[] = 'Site se creo correctamente';
        return redirect('/adminSites')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Sitio = Site::find($id);
        return view('modificarSite', ['elemento' => $Sitio, 'datos' => 'active']);
    }

    public function validar(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required|min:2|max:30',
                'descripcion' => 'max:100',
                'coordenadas' => 'max:60',
                'rangoIp' => 'ipv4',
                'ipDisponible' => 'ipv4',
                'distancia' => 'nullable|numeric|min:0|max:999'
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $nombre = $request->input('nombre');
        $descripcion = $request->input('descripcion');
        $coordenadas = $request->input('coordenadas');
        $rangoIp = $request->input('rangoIp');
        $ipDisponible = $request->input('ipDisponible');
        $distancia = $request->input('distancia');
        $site = Site::find($request->input('id'));
        $this->validar($request);
        $site->nombre = $nombre;
        $site->descripcion = $descripcion;
        $site->coordenadas = $coordenadas;
        $site->rangoIp = $rangoIp;
        $site->ipDisponible = $ipDisponible;
        $site->distancia = $distancia;
        $respuesta [] = 'Se cambió con exito:';
        if ($site->nombre != $site->getOriginal()['nombre']) {
            $respuesta [] = ' Nombre: ' . $site->getOriginal()['nombre'] . ' POR ' . $site->nombre;
        }
        if ($site->descripcion != $site->getOriginal()['descripcion']) {
            $respuesta []= ' Descripción: ' . $site->getOriginal()['descripcion'] . ' POR ' . $site->descripcion;
        }
        if ($site->coordenadas != $site->getOriginal()['coordenadas']) {
            $respuesta []= ' Coordenadas: ' . $site->getOriginal()['coordenadas'] . ' POR ' . $site->coordenadas;
        }
        if ($site->rangoIp != $site->getOriginal()['rangoIp']) {
            $respuesta []= ' Rango Ip: ' . $site->getOriginal()['rangoIp'] . ' POR ' . $site->rangoIp;
        }
        if ($site->ipDisponible != $site->getOriginal()['ipDisponible']) {
            $respuesta []= ' Ip Disponible: ' . $site->getOriginal()['ipDisponible'] . ' POR ' . $site->ipDisponible;
        }
        if ($site->distancia != $site->getOriginal()['distancia']) {
            $respuesta []= ' Distancia: ' . $site->getOriginal()['distancia'] . ' POR ' . $site->distancia;
        }
        $site->save();
        return redirect('adminSites')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}
