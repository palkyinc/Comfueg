<?php

namespace App\Http\Controllers;

use App\Models\Antena;
use App\Models\Equipo;
use App\Models\Panel;
use App\Models\Producto;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Axiom\Rules\MacAddress;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

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
        $Equipo->mac_address = strtoupper($request->input('mac_address'));
        if($request->input('ip') === null)
        {
            $Equipo->ip = '0.0.0.0';
        }else {
            $Equipo->ip = $request->input('ip');
        }
        $Equipo->num_antena = $request->input('num_antena');
        $Equipo->comentario = $request->input('comentario');
        $Equipo->fecha_alta = new DateTime();
        $Equipo->save();
        $respuesta[] = 'El Equipo se creo correctamente';
        return redirect('/adminEquipos?mac_address=' . $Equipo->mac_address)->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Equipo $equipo)
    {
        ##
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
    ##https://github.com/mattkingshott/axiom/blob/master/README.md
    ##,'unique:equipos,mac_address,' . $Equipo->id
    
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
    
    public function validar(Request $request, $idEquipo = "", $esApi = false)
    {
        $condicion = [
                'nombre' => 'required|min:2|max:45',
                'num_dispositivo' => 'required|numeric|min:1|max:99999',
                'num_antena' => 'required|numeric|min:1|max:99999',
                'ip' => 'nullable|ipv4',
                'fecha_alta' => 'date',
                'fecha_baja' => 'nullable|date',
                'comentario' => 'max:100'
        ];
        if ($idEquipo) {
            $condicion['mac_address'] = ['unique:equipos,mac_address,' . $idEquipo, 'required', new MacAddress];
        } else {
            $condicion['mac_address'] = ['unique:equipos,mac_address', 'required', new MacAddress];
        }
        if ($esApi){
            $validator = Validator::make(
            $request->all(), $condicion);
            if ($validator->fails()) {
                return($validator->errors());
            }else {
                return false;
            }
        }else {
            $request->validate($condicion);
        }
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
        $Equipo->mac_address = strtoupper($mac_address);
        if($ip === null)
        {
            $Equipo->ip = '0.0.0.0';
        }
            else
            {
                $Equipo->ip = $ip;
            }
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
                $respuesta[] = 'CUIDADO!! Equipo pertenece a contrato activo de ' . $contrato->relCliente->getNomYApe();
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
        return redirect('adminEquipos?mac_address=' . $Equipo->mac_address)->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipo  $Equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipo $equipo)
    {
       ##
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
        return redirect('adminEquipos?mac_address=' . $Equipo->mac_address)->with('mensaje', $rta);
    }

    public function ipLibre($ip)
    {
        if (Equipo::ipLibrePaneles($ip, true) && Equipo::ipLibrePaneles($ip, false)){
            return true;   
        }
        return false;
    }

    ### API REST Functions

    public function getById ($id) {
        $equipo = Equipo::find($id);
        if ($equipo) {
            $equipo->num_dispositivo = Producto::find($equipo->num_dispositivo);
            $equipo->num_antena = Antena::find($equipo->num_antena);
            unset($equipo->password);
            unset($equipo->usuario);
            return response()->json($equipo, 200);
        } else {
            return response()->json(false, 200);
        }
    }
    public function existByMac($macaddress) {
        $regex = '/^(?:[0-9A-F]{2}[:]){5}(?:[0-9A-F]{2})$/';
        $rta['status']= false;
        $rta['datos'] = null;
        $rta['mensaje'] = null;
        if (preg_match($regex, $macaddress)) {
            if($equipo = Equipo::select('id', 'nombre', 'num_dispositivo', 'mac_address', 'ip', 'num_antena', 'fecha_alta', 'fecha_baja', 'comentario')
                                     ->where('mac_address', ($macaddress = strtoupper($macaddress)))
                                     ->first()) {
                $equipo->num_dispositivo = Producto::where('id', $equipo->num_dispositivo)->first();
                $equipo->num_antena = Antena::where('id', $equipo->num_antena)->first();
                $rta['datos'] = $equipo;
                if ( ($contrato = Contrato::where('num_equipo', $equipo->id)->first()) || $panel = Panel::where('id_equipo', $equipo->id)->first()) 
                {
                    $rta['mensaje'] = ($contrato ? 
                                                    'Equipo asignado al Contrado' . ($contrato->baja ? '(Dado de baja)' : '') . ' N°: ' . $contrato->id . ', del Cliente: ' . ($contrato->relCliente->getNomyApe()) 
                                                    : 'Equipo asignado al Panel: ' . ($panel->ssid ?? '') );
                    $rta['status'] = true;
                }
            }
        }else {
                $rta['status']= true;
                $rta['mensaje'] = 'ERROR en Mac Address ingresado.';
            }
        
        return response()->json($rta, 200);
    }
    
    public function storeApiRest(Request $request)
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        if (!$rta = $this->validar($request, $request->input('id'), true)) {
            if ($request->input('id')) {
                $Equipo = Equipo::find($request->input('id'));
            }else {
                $Equipo = new Equipo;
                $Equipo->fecha_alta = new DateTime();
                $Equipo->mac_address = strtoupper($request->input('mac_address'));
            }
            $Equipo->nombre = $request->input('nombre');
            $Equipo->num_dispositivo = $request->input('num_dispositivo');
            if($request->input('ip') === null)
            {
                $Equipo->ip = '0.0.0.0';
            }else {
                $Equipo->ip = $request->input('ip');
            }
            $Equipo->num_antena = $request->input('num_antena');
            $Equipo->comentario = $request->input('comentario');
            $Equipo->save();
            $Equipo = $Equipo->fresh();
            $rta = $Equipo->id;
            $codigo = 200;
        }else {
            $codigo = 400;    
        }
        return response()->json($rta, $codigo);
    }
}##fin de la clase
