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
        $paneles = Panel::select("*")
            ->whereRaw("UPPER(num_site) LIKE (?)", ["{$sitio}"])
            ->whereRaw("UPPER(ssid) LIKE (?)", ["%{$ssid}%"])
            ->paginate(10);
        return view('adminPaneles', ['paneles' => $paneles, 'datos' => 'active', 'sitios' => $sitios]);
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
    public function validar(Request $request, Panel $Panel)
    {
        $request->validate(
            [
                'ssid' => 'required|min:2|max:15',
                'rol' => 'required|min:1|max:10',
                'id_equipo' => 'required|numeric|min:1|max:99999|unique:equipos,mac_address,' . $Panel->id,
                'num_site' => 'required|numeric|min:1|max:99999',
                'panel_ant' => 'nullable|numeric|min:1|max:99999',
                'activo' => 'boolean',
                'comentario' => 'max:65535',
                'cobertura' => 'nullable|mimes:jpg,jpeg,png,gif,svg,webp|max:4096'
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
        $activo = $request->input('activo');
        $comentario = $request->input('comentario');
        $Panel = Panel::find($request->input('id'));
        $this->validar($request, $Panel);
        $cobertura = $this->subirImagen($request);
        if ($cobertura !== $Panel->cobertura && $cobertura != 'sinMapa.svg') {
            $Panel->cobertura = $cobertura;
        }
        $Panel->ssid = $ssid;
        $Panel->rol = $rol;
        $Panel->id_equipo = $id_equipo;
        $Panel->num_site = $num_site;
        $Panel->panel_ant = $panel_ant;
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
        if ($Panel->comentario != $Panel->getOriginal()['comentario']) {
            $respuesta[] = ' comentario: ' . $Panel->getOriginal()['comentario'] . ' POR ' . $Panel->comentario;
        }
        $Panel->save();
        return redirect('adminPaneles')->with('mensaje', $respuesta);
    }

    public function subirImagen(Request $request)
    {
        //si no enviaron archivo
        $prdImagen = 'sinMapa.svg';

        //si enviaron archivo
        if ($request->file('cobertura')) {
            //renombrar archivo
            # time().extension
            $prdImagen = time() . '.' . $request->file('cobertura')->clientExtension();

            //subir imagen a directorio productos
            $request->file('cobertura')
            ->move(public_path('/imgUsuarios'), $prdImagen);
        }
        return $prdImagen;
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
        $equiposTodos = Equipo::get();
        $paneles = Panel::get();
        $panel = Panel::find($id);
        $equipos;
        foreach ($equiposTodos as $equipo) {
            $encontrado = false;
            foreach ($paneles as $unPanel) {
                if ($equipo->id == $unPanel->id_equipo) {
                    $encontrado = true;
                }
            }
            if (!$encontrado && !$equipo->fecha_baja) {
                $equipos[] = $equipo;
            }
        }
        $equipos[] = Equipo::find($panel->id_equipo);
        $sitios = Site::get();
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
    public function activar(Request $request)
    {
        $Panel = Panel::find($request->input('idEdit'));
        if ($Panel->activo) {
            $Panel->activo = false;
            $respuesta = 'Se desactivo ID: ' . $Panel->id;
        } else {
            $Panel->activo = true;
            $respuesta = 'Se activo ID: ' . $Panel->id;
        }
        $Panel->save();
        return redirect('adminPaneles')->with('mensaje', 'Se cambió con exito ->' . $respuesta);
    }
}//fin de la clase
