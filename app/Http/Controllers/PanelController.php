<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Site;
use App\Models\Panel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Custom\GatewayMikrotik;

class PanelController extends Controller
{
    public function index(Request $request)
    {
        $sitios = Site::get();
        $sitio = $request->input('sitio');
        $ssid = strtoupper($request->input('ssid'));
        if ($sitio || $ssid)
        {
            //dd($sitio);
            $paneles = Panel::select("*")
                ->whereRaw("UPPER(ssid) LIKE (?)", ["%{$ssid}%"])
                ->whereRaw("UPPER(num_site) LIKE (?)", ["%{$sitio}"])
                ->paginate(10);
        } else  {
            $paneles = Panel::select('*')->paginate(10);
        }
        return view('adminPaneles', [
            'paneles' => $paneles,
            'datos' => 'active',
            'sitios' => $sitios,
            'sitio' => $sitio,
            'ssid' => $ssid
        ]);
    }
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
        $Panel->cable_fecha = $request->input('cable_fecha');
        $Panel->cable_tipo = $request->input('cable_tipo');
        $Panel->activo = TRUE;
        $Panel->comentario = $request->input('comentario');
        $Panel->save();
        $respuesta['success'][] = 'El Panel se creó correctamente';
        return redirect('/adminPaneles')->with('mensaje', $respuesta);
    }
    public function storeDns(Request $request)
    {
        //dd($request);
        $request->validate(
            [
                'inputCheck'   => ['required', Rule::in(['external', 'passThrough', 'none'])],
                'dns_server_ip' => 'ip',
        ]);
        $dns_server_array['server'] = $request->dns_server_ip;
        $dns_server_array['passThrough'] = false;
        $dns_server_array['external'] = false;
        if ($request->inputCheck === 'passThrough') {
            $dns_server_array['passThrough'] = true;
        } elseif ($request->inputCheck === 'external') {
            $dns_server_array['external'] = true;
        }
        return redirect('adminPaneles')->with('mensaje',
            $this->addDeleteDns($request->panel_id, $dns_server_array, false, true)
        );
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
                'cable_fecha' => 'nullable|date',
                'cable_tipo' => 'max:50',
                'activo' => 'boolean',
                'comentario' => 'max:100'
            ]
        );
    }
    public function update(Request $request)
    {
        $ssid = $request->input('ssid');
        $rol = $request->input('rol');
        $id_equipo = $request->input('id_equipo');
        $num_site = $request->input('num_site');
        $panel_ant = $request->input('panel_ant');
        $altura = $request->input('altura');
        $cable_fecha = $request->input('cable_fecha');
        $cable_tipo = $request->input('cable_tipo');
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
        $Panel->cable_fecha = $cable_fecha;
        $Panel->cable_tipo = $cable_tipo;
        $Panel->activo = $activo;
        $Panel->comentario = $comentario;
        $respuesta['success'][] = 'Se cambió con exito en Panel con ID: ' . $Panel->id;
        if ($Panel->ssid != $Panel->getOriginal()['ssid']) {
            $respuesta['success'][] = ' ssid: ' . $Panel->getOriginal()['ssid'] . ' POR ' . $Panel->ssid;
        }
        if ($Panel->rol != $Panel->getOriginal()['rol']) {
            $respuesta['success'][] = ' Rol: ' . $Panel->getOriginal()['rol'] . ' POR ' . $Panel->rol;
        }
        if ($Panel->id_equipo != $Panel->getOriginal()['id_equipo']) {
            $respuesta['success'][] = ' Id Equipo: ' . $Panel->getOriginal()['id_equipo'] . ' POR ' . $Panel->id_equipo;
        }
        if ($Panel->num_site != $Panel->getOriginal()['num_site']) {
            $respuesta['success'][] = ' Num Site: ' . $Panel->getOriginal()['num_site'] . ' POR ' . $Panel->num_site;
        }
        if ($Panel->panel_ant != $Panel->getOriginal()['panel_ant']) {
            $respuesta['success'][] = ' Panel Ant: ' . $Panel->getOriginal()['panel_ant'] . ' POR ' . $Panel->panel_ant;
        }
        if ($Panel->altura != $Panel->getOriginal()['altura']) {
            $respuesta['success'][] = ' altura: ' . $Panel->getOriginal()['altura'] . ' POR ' . $Panel->altura;
        }
        if ($Panel->cable_fecha != $Panel->getOriginal()['cable_fecha']) {
            $respuesta['success'][] = ' cable_fecha: ' . $Panel->getOriginal()['cable_fecha'] . ' POR ' . $Panel->cable_fecha;
        }
        if ($Panel->cable_tipo != $Panel->getOriginal()['cable_tipo']) {
            $respuesta['success'][] = ' cable_tipo: ' . $Panel->getOriginal()['cable_tipo'] . ' POR ' . $Panel->cable_tipo;
        }
        if ($Panel->comentario != $Panel->getOriginal()['comentario']) {
            $respuesta['success'][] = ' comentario: ' . $Panel->getOriginal()['comentario'] . ' POR ' . $Panel->comentario;
        }
        $Panel->save();
        return redirect('adminPaneles')->with('mensaje', $respuesta);
    }
    public function show(Panel $panel)
    {
        //
    }
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
    public function editDns ($id)
    {
        $panel = Panel::find($id);
        $dnsList = json_decode($panel->dns_servers, true);
        if($dnsList === null)
        {
            $dnsList = [];
        }
        return view('modificarPanelDns', [
            'panel' => $panel,
            'datos' => 'active',
            'dnsList' => $dnsList
        ]);
    }
    public function destroy(Panel $panel)
    {
        //
    }
    public function destroyDns(Request $request)
    {
        $dns_server_array['server'] = $request->dns_server;
        return redirect('adminPaneles')->with('mensaje',
            $this->addDeleteDns($request->panel_id, $dns_server_array, true)
        );
    }
    public function addDeleteDns ($panel_id, $dns_server_array, $delete, $tipo = null)
    {
        $panel = Panel::find($panel_id);
        $dns_servers = json_decode($panel->dns_servers, true);
        $borrado = false;
        $agregado = false;
        if ($delete) {
            foreach ($dns_servers as $key => $value)
            {
                if($value['server'] === $dns_server_array['server'])
                {
                    unset($dns_servers[$key]);
                    $respuesta['success'][] = 'Se eliminó el dns:' . $dns_server_array['server'];
                    $borrado = true;
                }
            }
        } else {
            $dns_servers[] = $dns_server_array;
            $respuesta['success'][] = 'Se agregó el dns:' . $dns_server_array['server'];
            $agregado = true;
        }
        if (!$borrado && !$agregado)
        {
            $respuesta['error'][] = 'No se pudo eliminar o agregar el dns:' . $dns_server_array['server'];
        } else {
            $panel->dns_servers = json_encode($dns_servers);
            $panel->save();
            $apiMikro = GatewayMikrotik::getConnection($panel->relEquipo->ip, $panel->relEquipo->getUsuario(), $panel->relEquipo->getPassword());
            $apiMikro->checkDnsServer($panel->dns_servers);
        }
        return($respuesta);
    }
    public function activar($id)
    {
        $Panel = Panel::find($id);
        if ($Panel->activo) {
            $Panel->activo = false;
            $respuesta['success'][] = 'Se desactivo ID: ' . $Panel->id;
        } else {
            $Panel->activo = true;
            $respuesta['success'][] = 'Se activo ID: ' . $Panel->id;
        }
        $Panel->save();
        return redirect('adminPaneles')->with('mensaje', $respuesta);
    }

    ### API Methods

    public function getPanels() {
        $Paneles = Panel::select('id', 'ssid')
                        ->where('activo', 1)
                        ->orderBy('num_site')
                        ->whereIn('rol', ['PANEL'])
                        ->get();
        return response()->json($Paneles, 200);
    }
    
    public function getPanelById($id) {
        $panel = Panel::find($id);
        if ($panel) {
            return response()->json($panel, 200);
        }else {
            return response()->json(false, 200);
        }
    }
}//fin de la clase
