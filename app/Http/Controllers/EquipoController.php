<?php

namespace App\Http\Controllers;

use App\Models\Antena;
use App\Models\Equipo;
use App\Models\Producto;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Axiom\Rules\MacAddress;
use DateTime;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ip = strtoupper($request->input('ip'));
        $mac_address = strtoupper($request->input('mac_address'));
        $equipos = Equipo::select("*")
            ->whereRaw("UPPER(ip) LIKE (?)", ["%{$ip}%"])
            ->whereRaw("UPPER(mac_address) LIKE (?)", ["%{$mac_address}%"])
            ->paginate(10);
        return view('adminEquipos', ['equipos' => $equipos, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dispositivos = Producto::get();
        $antenas = Antena::get();
        return view('agregarEquipo', [
            'dispositivos' => $dispositivos,
            'antenas' => $antenas,
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
        $this->validar($request);
        $Equipo = new Equipo;
        $Equipo->nombre = $request->input('nombre');
        $Equipo->num_dispositivo = $request->input('num_dispositivo');
        $Equipo->mac_address = $request->input('mac_address');
        $Equipo->ip = $request->input('ip');
        $Equipo->num_antena = $request->input('num_antena');
        $Equipo->comentario = $request->input('comentario');
        $Equipo->fecha_alta = new DateTime();
        $Equipo->save();
        $respuesta[] = 'El Equipo se creo correctamente';
        return redirect('/adminEquipos')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Equipo $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dispositivos = Producto::get();
        $antenas = Antena::get();
        $equipo = Equipo::find($id);
        return view('modificarEquipo', [
            'elemento' => $equipo,
            'dispositivos' => $dispositivos,
            'antenas' => $antenas,
            'datos' => 'active'
        ]);
    }
    //https://github.com/mattkingshott/axiom/blob/master/README.md
    //,'unique:equipos,mac_address,' . $Equipo->id
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function editUserPass($id)
    {
        $equipo = Equipo::getUserPassword($id);
        return view('modificarEquipoUserPass', ['nodos' => 'active', 'elemento' => $equipo]);
    }
    
    public function validar(Request $request, $idEquipo = "")
    {
        if ($idEquipo) {
            $condicion = ['mac_address' => ['unique:equipos,mac_address,' . $idEquipo, 'required', new MacAddress]];
        } else {
            $condicion = ['mac_address' => ['unique:equipos,mac_address', 'required', new MacAddress]];
        }
        $request->validate($condicion);
        $request->validate(
            [
                'nombre' => 'required|min:2|max:45',
                'num_dispositivo' => 'required|numeric|min:1|max:99999',
                'num_antena' => 'required|numeric|min:1|max:99999',
                'ip' => 'required|ipv4',
                'fecha_alta' => 'date',
                'fecha_baja' => 'nullable|date',
                'comentario' => 'max:65535'
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function updateUserpass(Request $request)
    {
        $request->validate(
            [
                'usuario' => 'required|min:4|max:20',
                'password' => [
                            'required',
                            'min:8',
                            'max:20',
                            'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@._,"&()=+-]).*$/'
                            ]
            ],
            [
                'password.required' => 'El password es requerido',
                'password.min' => 'Password: Como mínimo 8 caracteres',
                'password.max' => 'Password: Como máximo 20 caracteres',
                'password.regex'=> 'Password: Al menos una mayúscula, una minúcula, un número y alguno de estos: !$#%@._"&()=+-, '
            ]

        );
        Equipo::setUserPass($request->input('id'), $request->input('usuario'), $request->input('password'));
        $respuesta[] = 'Se cambió con exito Usuario y Contraseña del equipo con ID:' . $request->input('id');
        return redirect('adminPaneles')->with('mensaje', $respuesta);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $nombre = $request->input('nombre');
        $num_dispositivo = $request->input('num_dispositivo');
        $mac_address = $request->input('mac_address');
        $ip = $request->input('ip');
        $num_antena = $request->input('num_antena');
        $fecha_alta = $request->input('fecha_alta');
        $fecha_baja = $request->input('fecha_baja');
        $comentario = $request->input('comentario');
        $Equipo = Equipo::find($request->input('id'));
        $this->validar($request, $Equipo->id);
        $Equipo->nombre = $nombre;
        $Equipo->num_dispositivo = $num_dispositivo;
        $Equipo->mac_address = $mac_address;
        $Equipo->ip = $ip;
        $Equipo->num_antena = $num_antena;
        $Equipo->fecha_alta = $fecha_alta;
        $Equipo->fecha_baja = $fecha_baja;
        $Equipo->comentario = $comentario;
        $respuesta[] = 'Se cambió con exito:';
        if ($Equipo->nombre != $Equipo->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $Equipo->getOriginal()['nombre'] . ' POR ' . $Equipo->nombre;
        }
        if ($Equipo->num_dispositivo != $Equipo->getOriginal()['num_dispositivo']) {
            $respuesta[] = ' Num Dispositivo: ' . $Equipo->getOriginal()['num_dispositivo'] . ' POR ' . $Equipo->num_dispositivo;
        }
        if ($Equipo->mac_address != $Equipo->getOriginal()['mac_address']) {
            $respuesta[] = ' Mac Address: ' . $Equipo->getOriginal()['mac_address'] . ' POR ' . $Equipo->mac_address;
        }
        if ($Equipo->ip != $Equipo->getOriginal()['ip']) {
            $respuesta[] = ' Ip: ' . $Equipo->getOriginal()['ip'] . ' POR ' . $Equipo->ip;
            $contrato = Contrato::where('num_equipo', $Equipo->id)->where('baja', false)->first();
            if ($contrato)
            {
                $respuesta[] = 'CUIDADO!! Equipo con contrato activo';
            }
        }
        if ($Equipo->num_antena != $Equipo->getOriginal()['num_antena']) {
            $respuesta[] = ' Num Antena: ' . $Equipo->getOriginal()['num_antena'] . ' POR ' . $Equipo->num_antena;
        }
        if ($Equipo->fecha_alta != $Equipo->getOriginal()['fecha_alta']) {
            $respuesta[] = ' Fecha Alta: ' . $Equipo->getOriginal()['fecha_alta'] . ' POR ' . $Equipo->fecha_alta;
        }
        if ($Equipo->fecha_baja != $Equipo->getOriginal()['fecha_baja']) {
            $respuesta[] = ' Fecha Baja: ' . $Equipo->getOriginal()['fecha_baja'] . ' POR ' . $Equipo->fecha_baja;
        }
        if ($Equipo->comentario != $Equipo->getOriginal()['comentario']) {
            $respuesta[] = ' Comentario: ' . $Equipo->getOriginal()['comentario'] . ' POR ' . $Equipo->comentario;
        }
        $Equipo->save();
        return redirect('adminEquipos')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipo  $Equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipo $equipo)
    {
        //
    }
    /**
     * Activate o desactivate equipo.
     *
     * @param  \App\Models\Equipo  $Equipo
     * @return \Illuminate\Http\Response
     */
    public function activar(Request $request)
    {
        $Equipo = Equipo::find($request->input('idEdit'));
        if (!$Equipo->fecha_baja) {
            $Equipo->fecha_baja = date('Y-m-d');
            $respuesta = 'Se desactivo el Equipo con Nombre: ' . $Equipo->nombre;
        }else {
            $Equipo->fecha_baja = null;
            $respuesta = 'Se activo el Equipo con Nombre: ' . $Equipo->nombre;
        }
        $Equipo->save();
        $rta [] = 'Se cambió con exito. ' . $respuesta;
        return redirect('adminEquipos')->with('mensaje', $rta);
    }
}//fin de la clase
