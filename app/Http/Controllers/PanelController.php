<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Site;
use App\Models\Panel;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sitios = Site::get();
        $sitio = $request->input('sitio');
        $ssid = strtoupper($request->input('ssid'));
        if ($sitio || $ssid)
        {
            $paneles = Panel::select("*")
                ->whereRaw("UPPER(ssid) LIKE (?)", ["%{$ssid}%"])
                ->whereRaw("UPPER(num_site) LIKE (?)", ["%{$sitio}"])
                ->paginate(10);
            } else  {
                $paneles = Panel::select('*')->paginate(10);
            }
        return view('adminPaneles', ['paneles' => $paneles, 'datos' => 'active', 'sitios' => $sitios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $equipos = Equipo::equiposSinAgregar();
        $equipos = $this->eliminarClientes($equipos);
        $paneles = Panel::get();
        $sitios = Site::get();
        return view('agregarPanel', [
            'equipos' => $equipos,
            'paneles' => $paneles,
            'sitios' => $sitios,
            'datos' => 'active',
            'seleccionado' => false,
            'roles' => ['PTPAP' => 'PTPAP', 'PTPST' => 'PTPST', 'PANEL' => 'PANEL', 'SWITCH' => 'SWITCH', 'GATEWAY' => 'GATEWAY']
        ]);
    }

    private function eliminarClientes($equipos)
    {
    foreach ($equipos as $key => $equipo) {
        $ipEquipo = explode(".", $equipo->ip);
        if ($ipEquipo[2] !== '0') {
            unset($equipos[$key]);
        }
    }
    return $equipos;
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
        $Panel = new Panel;
        $Panel->ssid = $request->input('ssid');
        $Panel->rol = $request->input('rol');
        $Panel->id_equipo = $request->input('id_equipo');
        $Panel->num_site = $request->input('num_site');
        $Panel->panel_ant = $request->input('panel_ant');
        $Panel->altura = $request->input('altura');
        $Panel->activo = TRUE;
        $Panel->comentario = $request->input('comentario');
        $Panel->save();
        $respuesta[] = 'El Panel se creo correctamente';
        return redirect('/adminPaneles')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idPanel = "")
    {
        if ($idPanel) {
            $condicion = 'required|numeric|min:1|max:99999|unique:equipos,mac_address,' . $idPanel;
        } else {
            $condicion = 'required|numeric|min:1|max:99999|unique:equipos,mac_address,';
        }
        $request->validate(
            [
                'ssid' => 'required|min:2|max:15',
                'rol' => 'required|min:1|max:10',
                'id_equipo' => $condicion,
                'num_site' => 'required|numeric|min:1|max:99999',
                'panel_ant' => 'nullable|numeric|min:1|max:99999',
                'altura' => 'nullable|numeric|min:1|max:999',
                'activo' => 'boolean',
                'comentario' => 'max:65535'
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ssid = $request->input('ssid');
        $rol = $request->input('rol');
        $id_equipo = $request->input('id_equipo');
        $num_site = $request->input('num_site');
        $panel_ant = $request->input('panel_ant');
        $altura = $request->input('altura');
        $activo = $request->input('activo');
        $comentario = $request->input('comentario');
        $Panel = Panel::find($request->input('id'));
        $this->validar($request, $Panel->id);
        $Panel->ssid = $ssid;
        $Panel->rol = $rol;
        $Panel->id_equipo = $id_equipo;
        $Panel->num_site = $num_site;
        $Panel->panel_ant = $panel_ant;
        $Panel->altura = $altura;
        $Panel->activo = $activo;
        $Panel->comentario = $comentario;
        $respuesta[] = 'Se cambió con exito:';
        if ($Panel->ssid != $Panel->getOriginal()['ssid']) {
            $respuesta[] = ' ssid: ' . $Panel->getOriginal()['ssid'] . ' POR ' . $Panel->ssid;
        }
        if ($Panel->rol != $Panel->getOriginal()['rol']) {
            $respuesta[] = ' Rol: ' . $Panel->getOriginal()['rol'] . ' POR ' . $Panel->rol;
        }
        if ($Panel->id_equipo != $Panel->getOriginal()['id_equipo']) {
            $respuesta[] = ' Id Equipo: ' . $Panel->getOriginal()['id_equipo'] . ' POR ' . $Panel->id_equipo;
        }
        if ($Panel->num_site != $Panel->getOriginal()['num_site']) {
            $respuesta[] = ' Num Site: ' . $Panel->getOriginal()['num_site'] . ' POR ' . $Panel->num_site;
        }
        if ($Panel->panel_ant != $Panel->getOriginal()['panel_ant']) {
            $respuesta[] = ' Panel Ant: ' . $Panel->getOriginal()['panel_ant'] . ' POR ' . $Panel->panel_ant;
        }
        if ($Panel->altura != $Panel->getOriginal()['altura']) {
            $respuesta[] = ' altura: ' . $Panel->getOriginal()['altura'] . ' POR ' . $Panel->altura;
        }
        if ($Panel->comentario != $Panel->getOriginal()['comentario']) {
            $respuesta[] = ' comentario: ' . $Panel->getOriginal()['comentario'] . ' POR ' . $Panel->comentario;
        }
        $Panel->save();
        return redirect('adminPaneles')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function show(Panel $panel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipos = Equipo::equiposSinAgregar();
        $equipos = $this->eliminarClientes($equipos);
        $paneles = Panel::get();
        $panel = Panel::find($id);
        $sitios = Site::get();
        $equipos[] = Equipo::find($panel->id_equipo);
        return view('modificarPanel', [
            'elemento' => $panel,
            'equipos' => $equipos,
            'paneles' => $paneles,
            'sitios' => $sitios,
            'datos' => 'active',
            'seleccionado' => false,
            'roles' => ['PTPAP' => 'PTPAP', 'PTPST' => 'PTPST', 'PANEL' => 'PANEL', 'SWITCH' => 'SWITCH', 'GATEWAY' => 'GATEWAY']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Panel $panel)
    {
        //
    }
    public function activar($id)
    {
        $Panel = Panel::find($id);
        if ($Panel->activo) {
            $Panel->activo = false;
            $respuesta[] = 'Se desactivo ID: ' . $Panel->id;
        } else {
            $Panel->activo = true;
            $respuesta[] = 'Se activo ID: ' . $Panel->id;
        }
        $Panel->save();
        return redirect('adminPaneles')->with('mensaje', $respuesta);
    }
}//fin de la clase
