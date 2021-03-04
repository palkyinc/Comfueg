<?php

namespace App\Http\Controllers;

use App\Models\Prueba;
use Illuminate\Http\Request;
use App\Custom\ubiquiti;
use App\Models\Panel;
use Illuminate\Support\Facades\Config;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Status 0 = red/desconectado | 1 = green/ok | 2 = orange/warning | 3 = sinColor/No Aplica
     * @return \Illuminate\Http\Response
     */
    public function testPanel($ip)
    {
        $ubiquiti   = new Ubiquiti($ip, $this->getPanelUser($ip), $this->getPanelPassword($ip));
        
        return response()->json($this->getStatusSignal($ubiquiti, $this->getPanelPorId($ip)), 200);
    }

    private function getStatusSignal ($ubiquiti, $panel)
    {
    $status = ($ubiquiti->status(true));
    $signal = ($ubiquiti->signal(true));
    $prueba = New Prueba();
    if ($status) {
        $panel->clientes = $status['wireless']['count'];
        $panel->canal = substr($status['wireless']['frequency'], 0, 4);
        $panel->save();
        $prueba->ip_equipo = $panel->relEquipo->ip;
        $statusLAN = 3;
        $rta =  [
            'status' => $prueba->contactado = 1,
            'Uptime' => ($this->uptime($status['host']['uptime'])),
            'Temperature' => ((isset($status['host']['temperature'])) ? $status['host']['temperature'] . "°C" : 'N/A '),
            'Hostname' => $prueba->nom_equipo = $status['host']['hostname'],
            'MacAdrress' => $prueba->mac_address = $status['wireless']['apmac'],
            'Firmware' => $prueba->firmware = ($status['host']['fwprefix'] ?? '') . $status['host']['fwversion'],
            'DevModel' => $prueba->dispositivo = $status['host']['devmodel'],
            'NetRole' => $status['host']['netrole'],
            'Clients' => $prueba->clientes_conec = $status['wireless']['count'],
            'statusClients' => ($status['wireless']['count'] > 25) ? (($status['wireless']['count'] > 29) ? 0 : 2) : 1,
            'SSID' => $prueba->ssid = $status['wireless']['essid'],
            'Signal' => $prueba->senial = ($signal['signal'] ?? '0') . 'dBm',
            'statusSignal' => (isset($signal['signal'])) ? (($signal['signal'] < -69) ? (($signal['signal'] < -75) ? 0 : 2) : 1) : 3,
            'NoiseFloor' => $prueba->ruido = ($signal['noisef'] ?? '0') . 'dBm',
            'ChannelWidth' => ((isset($signal['chwidth'])) ? $signal['chwidth'] : $signal['chbw']) . 'Mhz',
            'Frecuency' => $prueba->canal = $status['wireless']['frequency'],
            'CCQ' => $prueba->ccq = ($status['wireless']['ccq'] ?? 'N/A'),
            'statusCCQ' => (isset($status['wireless']['ccq'])) ? (($status['wireless']['ccq'] < 700) ? (($status['wireless']['ccq'] < 600) ? 0 : 2) : 1) : 3,
            'CpuUse' => $prueba->uso_cpu = (isset($status['host']['cpuload'])) ? (round($status['host']['cpuload']) . "%") : 'N/A ',
            'statusCpuUse' => (isset($status['host']['cpuload'])) ? ((round($status['host']['cpuload']) > 50) ? ((round($status['host']['cpuload']) > 85) ? 0 : 2) : 1) : 3,
            'MemFree' => $prueba->mem_libre = (isset($status['host']['freeram'])) ? (round(($status['host']['freeram'] / $status['host']['totalram']) * 100) . "%") : 'N/A',
            'statusMemFree' => (isset($status['host']['freeram'])) ? ((round($status['host']['freeram'] / $status['host']['totalram']) < 0.5) ? 0 : 1) : 3,
            'TX' => $prueba->tx = ($status['wireless']['txrate'] ?? ''),
            'statusTX' => (isset($status['wireless']['txrate']) ? (($status['wireless']['txrate'] < 20) ? 0 : 1) : 3),
            'RX' => $prueba->rx = ($status['wireless']['rxrate'] ?? ''),
            'statusRX' => (isset($status['wireless']['rxrate']) ? (($status['wireless']['rxrate'] < 20) ? 0 : 1) : 3),
            'LanSpeed' => $prueba->lan_velocidad = ($this->lanspeed($status, $statusLAN)),
            'statusLan' => $statusLAN
        ];
        $prueba->lan_conectado = ($statusLAN === 0) ? 0 : 1;
        $prueba->user_id = auth()->user()->id ?? null;
    } else {
        $rta = ['status' => $prueba->contactado = 0];
    }
    $prueba->save();
    return $rta;
    }

    private function getPanelPorId ($ip)
    {
        $paneles = Panel::where('activo', 1)->get();
        foreach ($paneles as $panel) {
            if($panel->relEquipo->ip === $ip)
            {
                return($panel);
            }
        }
        return(null);
    }
    private function getPanelUser($ip){
        return Config::get('constants.PANEL_USER');
    }
    private function getPanelPassword($ip){
        return Config::get('constants.PANEL_PASS');
    }

    private function lanspeed ($status, &$statusLAN)
    {
        for ($i = 0; $i < count($status['interfaces']); $i++) {
            if  (
                    (
                        $status['interfaces'][$i]['ifname'] == 'eth0' 
                        && 
                        $status['interfaces'][$i]['status']['plugged'] == 1
                        &&
                        (
                            $status['interfaces'][$i]['status']['duplex'] == 1
                            ||
                            $status['interfaces'][$i]['status']['duplex'] == 0
                        )
                    )
                    ||
                    (
                        $status['interfaces'][$i]['ifname'] == 'ath0' 
                        &&
                        $status['interfaces'][$i]['status']['plugged'] == 1
                        &&
                        (
                            $status['interfaces'][$i]['status']['duplex'] == 1
                            ||
                            $status['interfaces'][$i]['status']['duplex'] == 0
                        )
                    )
                )
                {
                $lanSpeed = $status['interfaces'][$i]['status']['speed'] . "Mb";
                $statusLAN = ($status['interfaces'][$i]['status']['speed'] >= 100) ? 1 : (($status['interfaces'][$i]['status']['speed'] == 10) ? 2 : 0);
                if ($status['interfaces'][$i]['status']['duplex'] == 1)
                {
                    $lanSpeed .= '-Full';
                } elseif ($status['interfaces'][$i]['status']['duplex'] == 0)
                        {
                            $lanSpeed .= '-Half';
                        }
            }
        }
        return isset($lanSpeed) ? $lanSpeed : 'Error';
    }
    private function uptime($segundos)
    {
        $rta = $segundos;
        $unidad = ' Segundo-s';
        if ($rta > 59) {
            $rta = $rta / 60;
            $unidad = ' Minuto-s';
            if ($rta > 59) {
                $rta = $rta / 60;
                $unidad = ' Hora-s';
                if ($rta > 23) {
                    $rta = $rta / 24;
                    $unidad = ' Días-s';
                    if ($rta > 30) {
                        $rta = $rta / 30;
                        $unidad = ' Mes-es';
                    }
                }
            }
        }
        return round($rta) . $unidad;
    }
    
    public function allPanels()
    {
        // sitio ip hostname
        $Paneles = Panel::where('activo', 1)
                        ->orderBy('num_site')
                        ->whereIn('rol', ['PANEL', 'PTPAP', 'PTPST'])
                        ->get();
        foreach ($Paneles as $panel) {
                $rta[] = ['sitio' => $panel->relSite->nombre, 'Hostname' => $panel->relEquipo->nombre, 'ip' => $panel->relEquipo->ip];
        }
        return response()->json($rta, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adminControlPanelNodos', [
            'nodos' => 'active',
            'website' => Config::get('constants.DOMINIO_COMFUEG'),
            'vuejs' => Config::get('constants.VUEJS_VERSION')
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
