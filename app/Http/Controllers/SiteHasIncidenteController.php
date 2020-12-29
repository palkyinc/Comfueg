<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use App\Models\Incidente_has_mensaje;
use App\Models\Panel;
use App\Models\Site;
use App\Models\Site_has_incidente;
use Illuminate\Http\Request;

class SiteHasIncidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sitios = Site::all();
        $incidentes = Site_has_incidente::where('final', null)->orderByDesc('inicio')->paginate(10);
        return view('adminSiteHasIncidentes', ['incidentes' => $incidentes,
                                                'abiertas' => true,
                                                'sitios' => $sitios,
                                                'nodos' => 'active',
                                                'sitioSelected' => false
                                                ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRebusqueda(Request $request)
    {
        $sitios = Site::all();
        $abiertas = (null != $request->input('abiertas')) ? true : false;
        $nodo = (null != $request->input('sitio')) ? $request->input('sitio') : false;
        if ($abiertas && !$nodo)
        {
            $incidentes = Site_has_incidente::where('final', null)->orderByDesc('inicio')->paginate();
        } elseif (!$abiertas && !$nodo) 
            {
                $incidentes = Site_has_incidente::orderByDesc('inicio')->paginate();
            }elseif ((!$abiertas && $nodo) || ($abiertas && $nodo)) 
                {
                if (!$abiertas)
                {
                    $incidentes = Site_has_incidente::orderByDesc('inicio')->paginate();
                } else
                    {
                        $incidentes = Site_has_incidente::where('final', null)->orderByDesc('inicio')->paginate();
                    }
                foreach ($incidentes as $key => $incidente)
                    {
                        if($incidente->relPanel->relSite->id != $nodo)
                        {
                            unset($incidentes[$key]);
                        }
                    }
                }
        return view('adminSiteHasIncidentes', ['incidentes' => $incidentes,
                                                'abiertas' => $abiertas,
                                                'sitios' => $sitios,
                                                'nodos' => 'active',
                                                'sitioSelected' => $nodo
                                                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paneles = Panel::all();
        return view('agregarSiteHasIncidente', ['paneles' => $paneles, 'completo' => 0, 'nodos' => 'activate']);
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
        $Afectados = $this->obtenerSitiosAfectados($request->input('afectado'));
        $incidente_global = new Site_has_incidente;
        $incidente_global->tipo = $request->input('tipo');
        $incidente_global->inicio = $request->input('inicio');
        $incidente_global->afectado = $request->input('afectado');
        $incidente_global->afectados_indi = $this->iterarAfectados($Afectados['paneles']);
        $incidente_global->sitios_afectados = $this->iterarAfectados($Afectados['sitios']);
        $incidente_global->barrios_afectados = $this->iterarAfectados($Afectados['barrios']);
        $incidente_global->causa = $request->input('causa');
        $incidente_global->mensaje_clientes = $request->input('mensaje_clientes');
        $incidente_global->user_creator = auth()->user()->id;
        $incidente_global->save();
        $incidente_global->enviarMail();
        $respuesta[] = 'Nuevo Incidente Global se creado correctamente';
        return redirect('/adminIncidencias')->with('mensaje', $respuesta);
    }

    private function iterarAfectados ($array)
    {
        foreach ($array as $element) {
            if (!isset($respuesta))
            {
                $respuesta = $element . ', ';
            }else 
                {
                    $respuesta .= $element . ', ';
                }
        }
        return $respuesta;
    }

    private function validar(Request $request)
    {
        $request->validate(
            [
                'tipo' => 'required|min:9|max:13',
                'inicio' => 'required|date',
                'afectado' => 'required|numeric|min:1|max:99999',
                'causa' => 'required|min:2|max:255',
                'mensaje_clientes' => 'required|min:2|max:255'
            ]
        );
    }
    private function obtenerSitiosAfectados($id)
    {
        $sitios = Site::all();
        foreach ($sitios as $sitio) {
            $ptpst = Panel::where('num_site', $sitio->id)->where('rol', 'PTPST')->first();
            if ($ptpst) {
                $panel_ant = Panel::find($ptpst->panel_ant);
                $arbol[] = [$panel_ant->relSite->id => $ptpst->relSite->id,
                            'actual' => $panel_ant->relSite->nombre,
                            'siguiente' => $ptpst->relSite->nombre];
            }
        }
        $afectado = Panel::find($id);
        if ($afectado->rol == 'PANEL') {
            $sitios_afectados[$afectado->relSite->id] = $afectado->relSite->nombre;
            $paneles_afectados[] = 'N/A';
            $barrios_afectados = $this->onePanel($afectado->id);
        } elseif ($afectado->rol == 'PTPST' || $afectado->rol == 'SWITCH') {
            $sitios_afectados[$afectado->relSite->id] = $afectado->relSite->nombre;
            $this->siguientesSitios($arbol, $afectado->relSite->id, $sitios_afectados);
            $paneles_afectados = $this->panelesAfectados($sitios_afectados);
            $barrios_afectados = $this->manyPanels($paneles_afectados);
        } elseif ($afectado->rol == 'PTPAP') {
            $sigAfectado = Panel::where('panel_ant', $afectado->id)->where('rol', 'PTPST')->first();
            $sitios_afectados[$sigAfectado->relSite->id] = $sigAfectado->relSite->nombre;
            $this->siguientesSitios($arbol, $sigAfectado->relSite->id, $sitios_afectados);
            $paneles_afectados = $this->panelesAfectados($sitios_afectados);
            $barrios_afectados = $this->manyPanels($paneles_afectados);
        }
        return ['sitios' => $sitios_afectados, 'paneles' => $paneles_afectados, 'barrios' => $barrios_afectados];
    }

    private function manyPanels ($paneles_afectados) 
    {
        foreach ($paneles_afectados as $key => $value) 
        {
            $aBarrios = Panel::find($key)->barrios;
            foreach ($aBarrios as $barrio) {
                $barrios_afectados[$barrio['id']] = $barrio['nombre'];
            }
        }
        return $barrios_afectados;
    }

    private function onePanel($id)
    {
        $aBarrios = Panel::find($id)->barrios;
            foreach ($aBarrios as $barrio) {
                $barrios_afectados[$barrio['id']] = $barrio['nombre'];
            }
        return $barrios_afectados;
    }

    private function panelesAfectados($arraySitios)
    {
        foreach ($arraySitios as $key => $value) {
            $panelesPorSitio = Panel::where('num_site', $key)->where('rol', 'PANEL')->get(); 
            foreach($panelesPorSitio as $panel)
            {
                $respuesta [$panel->id] = $panel->relEquipo->nombre;
            }
        }
        return ($respuesta);
    }

    private function siguientesSitios($arbol, $sitioActual, &$array_sitios)
    {
        for ($i = 0; $i < count($arbol); $i++) {
            if (isset($arbol[$i][$sitioActual])) {
                $array_sitios[$arbol[$i][$sitioActual]] = $arbol[$i]['siguiente'];
                $this->siguientesSitios($arbol, $arbol[$i][$sitioActual], $array_sitios);
            }
        }
        //dd($array_sitios);
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function show(Site_has_incidente $site_has_incidente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $incidente = Site_has_incidente::find($id);
        //dd(date("c", strtotime($incidente->inicio)));
        return view('modificarSIteHasincidente', ['sololectura' => true, 'incidente' => $incidente, 'nodos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $incidente = Site_has_incidente::find($request->input('id'));
        $this->updateValidar ($request);
        $incidente->inicio = $request->input('inicio');
        $incidente->final = $request->input('final');
        $incidente->mensaje_clientes = $request->input('mensaje_clientes');
        $incidente->save();
        $actualizacion = new Incidente_has_mensaje();
        $actualizacion->mensaje = $request->input('actualizacion');
        $actualizacion->incidente_id = $incidente->id;
        $actualizacion->user_creator = auth()->user()->id;
        $actualizacion->save();
        if(!$incidente->final)
        {
            $incidente->enviarMail('actualizacion');
        } else 
            {
                $incidente->enviarMail('cerrado');
            }
        $respuesta[] = 'Incidente Global ' . $incidente->crearNombre() . ' se ha actualizado correctamente';
        return redirect('/adminIncidencias')->with('mensaje', $respuesta);
    }

    private function updateValidar (Request $request)
    {
        $request->validate(
            [
                'inicio' => 'required|date',
                'final' => 'nullable|date',
                'mensaje_clientes' => 'required|min:2|max:255',
                'actualizacion' => 'required|min:2|max:255'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site_has_incidente $site_has_incidente)
    {
        //
    }
}
