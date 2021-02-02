<?php

namespace App\Http\Controllers;

use App\Models\Prueba;
use Illuminate\Http\Request;
use App\Custom\ubiquiti;
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
        
        return response()->json($this->getStatusSignal($ubiquiti), 200);
    }

    private function getStatusSignal ($ubiquiti)
    {
    $status = ($ubiquiti->status(true));
    $signal = ($ubiquiti->signal(true));
    if ($status) {
        $statusLAN = 3;
        $rta =  [
            'status' => 1,
            'Uptime' => ($this->uptime($status['host']['uptime'])),
            'Temperature' => ((isset($status['host']['temperature'])) ? $status['host']['temperature'] . "°C" : 'N/A '),
            'Hostname' => $status['host']['hostname'],
            'MacAdrress' => $status['wireless']['apmac'],
            'Firmware' => ($status['host']['fwprefix'] ?? '') . $status['host']['fwversion'],
            'DevModel' => $status['host']['devmodel'],
            'NetRole' => $status['host']['netrole'],
            'Clients' => $status['wireless']['count'],
            'statusClients' => ($status['wireless']['count'] > 26) ? 0 : 1,
            'SSID' => $status['wireless']['essid'],
            'Signal' => $signal['signal'] . 'dBm',
            'statusSignal' => ($signal['signal'] < -69) ? (($signal['signal'] < -75) ? 0 : 2) : 1,
            'NoiseFloor' => $signal['noisef'] . 'dBm',
            'ChannelWidth' => ((isset($signal['chwidth'])) ? $signal['chwidth'] : $signal['chbw']) . 'Mhz',
            'Frecuency' => $status['wireless']['frequency'],
            'CCQ' => ($status['wireless']['ccq'] ?? 'N/A'),
            'statusCCQ' => (isset($status['wireless']['ccq'])) ? (($status['wireless']['ccq'] < 700) ? (($status['wireless']['ccq'] < 600) ? 0 : 2) : 1) : 3,
            'CpuUse' => (isset($status['host']['cpuload'])) ? (round($status['host']['cpuload']) . "%") : 'N/A ',
            'statusCpuUse' => (isset($status['host']['cpuload'])) ? ((round($status['host']['cpuload']) > 70) ? 0 : 1) : 3,
            'MemFree' => (isset($status['host']['freeram'])) ? (round(($status['host']['freeram'] / $status['host']['totalram']) * 100) . "%") : 'N/A',
            'statusMemFree' => (isset($status['host']['freeram'])) ? ((round($status['host']['freeram'] / $status['host']['totalram']) < 0.5) ? 0 : 1) : 3,
            'TX' => ($status['wireless']['txrate'] ?? ''),
            'statusTX' => (isset($status['wireless']['txrate']) ? (($status['wireless']['txrate'] < 20) ? 0 : 1) : 3),
            'RX' => ($status['wireless']['rxrate'] ?? ''),
            'statusRX' => (isset($status['wireless']['rxrate']) ? (($status['wireless']['rxrate'] < 20) ? 0 : 1) : 3),
            'LanSpeed' => ($this->lanspeed($status, $statusLAN)),
            'statusLan' => $statusLAN
        ];
    } else {
        $rta = ['status' => 0];
    }
    return $rta;
    }

    public function getPanelUser($ip){
        return Config::get('constants.PANEL_USER');
    }
    public function getPanelPassword($ip){
        return Config::get('constants.PANEL_PASS');
    }

    private function lanspeed ($status, &$statusLAN)
    {
        for ($i = 0; $i < count($status['interfaces']); $i++) {
            if ($status['interfaces'][$i]['ifname'] == 'eth0') {
                $lanSpeed = $status['interfaces'][$i]['status']['speed'] . "Mb";
                $statusLAN = ($status['interfaces'][$i]['status']['speed'] == 100) ? 1 : (($status['interfaces'][$i]['status']['speed'] == 10) ? 2 : 0);
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
