<?php

namespace App\Http\Controllers;

use App\Models\Mac_address_exception;
use App\Models\Equipo;
use App\Models\Panel;
use Illuminate\Http\Request;
use App\Custom\ubiquiti;

class MacAddressExceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exceptions = Mac_address_exception::paginate(10);
        return view('adminMacExceptions', [
            'mac_exceptions' => $exceptions,
            'datos' => 'active'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($equipo_id)
    {
        $equipo = Equipo::select('id', 'nombre', 'num_dispositivo', 'ip', 'mac_address', 'comentario')
                        ->where('id', $equipo_id)
                        ->where('fecha_baja',  null)
                        ->first();
        $paneles = Panel::select('id', 'ssid', 'id_equipo')
                        ->where('activo', true)
                        ->where(function($query) {
                            $query->orWhere('rol', 'PANEL')
                                  ->orWhere('rol', 'GATEWAY');})
                        /* ->where('rol', 'PANEL')
                        //->orWhere('rol', ) */
                        ->orderbY('ssid', 'Asc')
                        ->get();
        return view('agregarMacException', [
            'paneles' => $paneles,
            'equipo' => $equipo,
            'datos' => 'active'
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
        $request->validate([
            'equipo_id' => 'required|numeric|min:1|max:99999',
            'panel_id' => 'required|numeric|min:1|max:99999',
            'descripcion' => 'required|min:3|max:30'
        ]);
        $equipo = Equipo::find($request->equipo_id);
        $panel = Panel::find($request->panel_id);
        $exception = new Mac_address_exception;
        $exception->equipo_id = $equipo->id;
        $exception->panel_id = $panel->id;
        $exception->description = $request->descripcion;
        ### Cargar Mac en panel
        $this->modificarMac (0, $exception); // 'ope' => 1 = Del, 0 = Add
        $exception->save();
        return redirect('adminEquipos')->with('mensaje_full',
            ['success' => ['Exception de Equipo: ' . $equipo->nombre . ' cargado con EXITO en ' . $panel->ssid . 'con IP¨: ' . $panel->relEquipo->ip]]
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mac_address_exception  $mac_address_exception
     * @return \Illuminate\Http\Response
     */
    public function show(Mac_address_exception $mac_address_exception)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mac_address_exception  $mac_address_exception
     * @return \Illuminate\Http\Response
     */
    public function edit(Mac_address_exception $mac_address_exception)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mac_address_exception  $mac_address_exception
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mac_address_exception $mac_address_exception)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mac_address_exception  $mac_address_exception
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $exception = Mac_address_exception::find($request->idEdit);
        $panel = $exception->relPanel->ssid;
        $ip = $exception->relPanel->relEquipo->ip;
        $equipo = $exception->relEquipo->nombre;
        ### Borrar de Panel
        $this->modificarMac (1, $exception); // 'ope' => 1 = Del, 0 = Add
        $exception->delete();
        return redirect('adminEquipos')->with('mensaje_full',
            ['success' => ['Exception de Equipo: ' . $equipo . ' ELIMINADO con EXITO en ' . $panel . 'con IP¨: ' . $ip]]
            );
    }
    private function modificarMac ($ope, $exception) // 'ope' => 1 = Del, 0 = Add
    {
        return ubiquiti::tratarMac(
            [
                'usuario' => $exception->relPanel->relEquipo->getUsuario(),
                'password' => $exception->relPanel->relEquipo->getPassword(),
                'ip' => $exception->relPanel->relEquipo->ip,
                'contrato' => 'exception',
                'macaddress' => $exception->relEquipo->mac_address,
                'ope' => $ope
            ]);
    }
}
