<?php

namespace App\Http\Controllers;

use App\Models\Entity_has_file;
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
        
        return view('adminSiteHasIncidentes', $this->getDatosIndex('INCIDENTE'));
    }
    private function getDatosIndex ($tipo)
    {
        $sitios = Site::all();
        $incidentes = Site_has_incidente::where('final', null)->where('tipo', $tipo)->orderByDesc('inicio')->paginate(10);
        foreach ($incidentes as $incidente) {
            $incidente->archivos = Entity_has_file::getArchivosEntidad(3, $incidente->id);
        }
        return [
            'incidentes' => $incidentes,
            'abiertas' => true,
            'sitios' => $sitios,
            'nodos' => 'active',
            'sitioSelected' => false
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDeuda()
    {
        return view('adminSiteHasDeudas', $this->getDatosIndex('DEUDA TECNICA'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRebusqueda(Request $request)
    {
        return view('adminSiteHasIncidentes', $this->getIndexRebusqueda($request, 'INCIDENTE'));
    }
    public function indexDeudasRebusqueda(Request $request)
    {
        return view('adminSiteHasDeudas', $this->getIndexRebusqueda($request, 'DEUDA TECNICA'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndexRebusqueda(Request $request, $tipo)
    {
        $sitios = Site::all();
        $abiertas = (null != $request->input('abiertas')) ? true : false;
        $nodo = (null != $request->input('sitio')) ? $request->input('sitio') : false;
        if ($abiertas && !$nodo)
        {
            $incidentes = Site_has_incidente::where('final', null)->where('tipo', $tipo)->orderByDesc('inicio')->paginate();
        } elseif (!$abiertas && !$nodo) 
            {
                $incidentes = Site_has_incidente::where('tipo', $tipo)->orderByDesc('inicio')->paginate();
            }elseif ((!$abiertas && $nodo) || ($abiertas && $nodo)) 
                {
                if (!$abiertas)
                {
                    $incidentes = Site_has_incidente::where('tipo', $tipo)->orderByDesc('inicio')->paginate();
                } else
                    {
                        $incidentes = Site_has_incidente::where('tipo', $tipo)->where('final', null)->orderByDesc('inicio')->paginate();
                    }
                foreach ($incidentes as $key => $incidente)
                    {
                        if($incidente->relPanel->relSite->id != $nodo)
                        {
                            unset($incidentes[$key]);
                        }
                    }
                }
        foreach ($incidentes as $incidente) {
            $incidente->archivos = Entity_has_file::getArchivosEntidad(3, $incidente->id);
        }
        return ['incidentes' => $incidentes,
                'abiertas' => $abiertas,
                'sitios' => $sitios,
                'nodos' => 'active',
                'sitioSelected' => $nodo
                ];
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDeuda()
    {
        $paneles = Panel::all();
        $deudas = Site_has_incidente::where('tipo', 'DEUDA TECNICA')->where('final', null)->get();
        return view('agregarSiteHasDeuda', ['deudas' => $deudas, 'paneles' => $paneles, 'completo' => 0, 'nodos' => 'activate']);
    }

    public function createArchivoIncidente($id)
    {
        return view('agregarArchivoIncidente', ['incidente_id' => $id, 'nodos' => 'active']);
    }

    public function storeDeuda(Request $request)
    {
        $this->validar($request, true);
        $deuda = new Site_has_incidente;
        $deuda->tipo = $request->input('tipo');
        $deuda->inicio = $request->input('inicio');
        $deuda->afectado = $request->input('afectado');
        $deuda->causa = $request->input('causa');
        $deuda->mensaje_clientes = $request->input('mensaje_clientes');
        $deuda->user_creator = auth()->user()->id;
        $deuda->precedencia = $request->input('precedencia');
        $deuda->fecha_tentativa = $request->input('fecha_tentativa');
        $deuda->prioridad = $request->input('prioridad');
        $deuda->save();
        $deuda->enviarMail(false, true);
        if ($request->hasfile('scheme_file')) {
            $this->tratarArchivos($request, $deuda);
        }
        $respuesta[] = 'Nueva Deuda Técnica se ha creado correctamente';
        return redirect('/adminDeudasTecnica')->with('mensaje', $respuesta);
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
        if ($request->hasfile('scheme_file')) {
            $this->tratarArchivos($request, $incidente_global);
        }
        $incidente_global->enviarMail();
        $respuesta[] = 'Nuevo Incidente Global se ha creado correctamente';
        return redirect('/adminIncidencias')->with('mensaje', $respuesta);
    }

    private function tratarArchivos ($request, $incidente_global)
    {
        $request->validate([
            'scheme_file' => 'required',
            'scheme_file.*' => 'mimes:pdf,jpg,jpeg,png|max:4096'
        ]);
        foreach ($request->file('scheme_file') as $archivo) {
            try {
                Entity_has_file::grabarPdfImage($archivo, $incidente_global->id, 3);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
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

    private function validar(Request $request, $esDeuda = False)
    {
        if (!$esDeuda)
        {
            $mensajeClientes = 'required|min:2|max:255';
        } else
            {
                $mensajeClientes = 'required|min:2|max:40';
            }
        $request->validate(
            [
                'tipo' => 'required|min:9|max:13',
                'inicio' => 'required|date',
                'fecha_tentativa' => 'nullable|date',
                'afectado' => 'required|numeric|min:1|max:99999',
                'causa' => 'required|min:2|max:255',
                'prioridad' => 'required|numeric|min:1|max:10',
                'mensaje_clientes' => $mensajeClientes
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
        $archivos = Entity_has_file::where('modelo_id', 3)->where('entidad_id', $id)->get();
        //dd(date("c", strtotime($incidente->inicio)));
        return view('modificarSIteHasincidente', ['archivos' => $archivos, 'incidente' => $incidente, 'nodos' => 'active']);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function editDeuda($id)
    {
        $incidente = Site_has_incidente::find($id);
        $archivos = Entity_has_file::where('modelo_id', 3)->where('entidad_id', $id)->get();
        $deudas = Site_has_incidente::where('tipo', 'DEUDA TECNICA')->where('final', null)->get();
        return view('modificarSiteHasDeuda', ['deudas' => $deudas , 'archivos' => $archivos, 'incidente' => $incidente, 'nodos' => 'active']);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function editArchivosIncidente($id)
    {
        $incidente = Site_has_incidente::find($id);
        $archivos = Entity_has_file::where('modelo_id', 3)->where('entidad_id', $id)->paginate();
        return view('adminArchivosIncidente', ['files' => $archivos, 'incidente' => $incidente, 'nodos' => 'active']);
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
        $actualizacion = new Incidente_has_mensaje();
        $actualizacion->mensaje = $request->input('actualizacion');
        $actualizacion->incidente_id = $incidente->id;
        $actualizacion->user_creator = auth()->user()->id;
        if ($request->hasfile('scheme_file')) {
            $this->tratarArchivos($request, $incidente);
        }
        $incidente->save();
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
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function updateDeuda(Request $request)
    {
        $incidente = Site_has_incidente::find($request->input('id'));
        $this->updateValidar ($request, true);
        $incidente->inicio = $request->input('inicio');
        $incidente->final = $request->input('final');
        $incidente->fecha_tentativa = $request->input('fecha_tentativa');
        $incidente->prioridad = $request->input('prioridad');
        $incidente->precedencia = $request->input('precedencia');
        $actualizacion = new Incidente_has_mensaje();
        $actualizacion->mensaje = $request->input('actualizacion');
        $actualizacion->incidente_id = $incidente->id;
        $actualizacion->user_creator = auth()->user()->id;
        if ($request->hasfile('scheme_file')) {
            $this->tratarArchivos($request, $incidente);
        }
        $incidente->save();
        $actualizacion->save();
        if(!$incidente->final)
        {
            $incidente->enviarMail('actualizacion', true);
        } else 
            {
                $incidente->enviarMail('cerrado', true);
            }
        $respuesta[] = 'Deuda Técnica con título " ' . $incidente->mensaje_clientes . '" se ha actualizado correctamente';
        return redirect('/adminDeudasTecnica')->with('mensaje', $respuesta);
    }

    public function updateArchivoIncidente(Request $request)
    {
        if ($request->hasfile('scheme_file')) {
            $request->validate([
                'scheme_file' => 'required',
                'scheme_file.*' => 'mimes:pdf,jpg,jpeg,png|max:10240'
            ]);
            foreach ($request->file('scheme_file') as $archivo) {
                try {
                    Entity_has_file::grabarPdfImage($archivo, $request->input('incidenteId'), 3);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        }
        return redirect('adminArchivosIncidente/' . $request->input('incidenteId'));
    }

    private function updateValidar (Request $request, $esDeuda = false)
    {
        if (!$esDeuda)
        {
            $request->validate(
                [
                    'inicio' => 'required|date',
                    'final' => 'nullable|date',
                    'mensaje_clientes' => 'required|min:2|max:255',
                    'actualizacion' => 'required|min:2|max:500',
                ]
            );
        } else 
            {
                $request->validate(
                    [
                        'inicio' => 'required|date',
                        'final' => 'nullable|date',
                        'fecha_tentativa' => 'nullable|date',
                        'prioridad' => 'required|numeric|min:1|max:10',
                        'actualizacion' => 'required|min:2|max:500',
                    ]
                );
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site_has_incidente  $site_has_incidente
     * @return \Illuminate\Http\Response
     */
    public function destroyArchivo($id)
    {
        $aBorrar = Entity_has_file::find($id);
        //$incidente_id = $aBorrar->entidad_id;
        $aBorrar->deleteArchivo();
        $aBorrar->delete();
        //dd($aBorrar);
        $respuesta[] = 'Archivo se eliminó correctamente';
        return redirect('adminArchivosIncidente/' . $aBorrar->entidad_id)->with('mensaje', $respuesta);
    
    }
}
