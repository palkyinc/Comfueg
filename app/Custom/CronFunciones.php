<?php

namespace App\Custom;
use Illuminate\Support\Facades\Config;
use App\Models\Panel;
use App\Models\Plan;
use App\Models\Proveedor;
use App\Models\Site_has_incidente;
use App\Models\Mail_group;
use App\Models\Contadores_mensuales;
use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use App\Mail\DeudaTecnicaResumen;
use App\Mail\CambioDeEstadoEnProveedor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;


abstract class CronFunciones
{

    public static function setClockAndResetGateway ()
    {
        $gateways = self::getGateways();
        foreach ($gateways as $elemento)
        {
            $gateway = Panel::find($elemento);
            $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
            if ($apiMikro)
            {
                $apiMikro->setClock();
                $apiMikro->makeBackup();
                $apiMikro->resetGateway();
            }
        }
    }
    public static function readCounterGateway()
    {
        $gateways = self::getGateways();
        foreach ($gateways as $elemento)
        {
                $gateway = Panel::find($elemento);
                $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
                if ($apiMikro) 
                {
                        $allData = $apiMikro->getGatewayData();
                        unset($apiMikro);
                        ### abrir el archivo
                        ### leer el array -> convertir de json a array
                        foreach ($allData['hotspotUser'] as $elemento)
                        {
                                if (isset($elemento['comment']) && is_numeric($elemento['comment'])) 
                                {
                                        $contador_mensual = Contadores_mensuales::where('contrato_id', $elemento['comment'])->first();
                                        if (!$contador_mensual)
                                        {
                                                $contador_mensual = new Contadores_mensuales();
                                                $contador_mensual->contrato_id = $elemento['comment'];
                                        }
                                        $contador_mensual->anio = date('Y');
                                        $contador_mensual->ultimo_mes = date('m');
                                        $contador_mensual->setMounthCounter($elemento['bytes-in'] + $elemento['bytes-out']);
                                        $contador_mensual->save();
                                }
                        }
                } else {
                        return 'Error al contactar al Gateway' . $gateway->relEquipo->ip;
                }
        }
        return false;
    }

    public static function resetCounter($mensual = false)
    {
        $gateways = self::getGateways();
        foreach ($gateways as $elemento) 
        {
            $gateway = Panel::find($elemento);
            $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
            if ($apiMikro) 
            { 
                if ($mensual)
                {
                        $apiMikro->resetCounterMensual();
                }
                else   {
                                $apiMikro->resetCounter();
                        }    
            }
        }
    }

    public static function generarArchivoSem($dias = 1)
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $ayer = date('Ymd', strtotime(date('Ymd')."- $dias days"));
        if (file_exists('../storage/Crons/' . $ayer . '.dat'))
        {
                $file = fopen('../storage/Crons/' . $ayer . '.dat', 'r');
                while(!feof($file))
                {
                        $linea = explode(';', trim(fgets($file)));
                        if (isset($linea[2]))
                        {
                                $salida[$linea[0]]['up'][$linea[1]] = isset($linea[2]) ? $linea[2] : 0;
                                $salida[$linea[0]]['down'][$linea[1]] = isset($linea[3]) ? $linea[3] : 0;
                        }
                }
                fclose($file);
                foreach ($salida as $cliente => $elemento)
                {
                        ###      completar con 0 si le faltan datos
                        if (count($elemento['down']) < 1440)
                        {
                                for ($h=0; $h < 24; $h++)
                                { 
                                        for ($m=0; $m < 60; $m++)
                                        { 
                                                $horaTest = str_pad(strval($h), 2, '0', STR_PAD_LEFT) . '.' . str_pad(strval($m), 2, '0', STR_PAD_LEFT);
                                                if (!isset($elemento['down'][$horaTest]))
                                                {
                                                        $elemento['down'][$horaTest] = "0";
                                                }
                                                if (!isset($elemento['up'][$horaTest]))
                                                {
                                                        $elemento['up'][$horaTest] = "0";
                                                }
                                        }
                                }
                                ksort($elemento['up']);
                                ksort($elemento['down']);
                        }
                        $up = null;
                        $down = null;
                        $time = null;
                        $upAnterior = null;
                        $downAnterior = null;
                        $i = 0;
                        $gapMinuto = 7;
                        foreach ($elemento['down'] as $hora => $value)
                        {
                                if ($i == 0)
                                {
                                        if (!($upAnterior) && !($downAnterior))
                                        {
                                                $up[$hora] = 0;
                                                $down[$hora] = 0;
                                                $i = $gapMinuto;
                                        }
                                        else
                                        {
                                                $up[$hora] = ($dato = ($elemento['up'][$hora] - $upAnterior)*8/1024/1024/(60*$gapMinuto)) > 0 ? round($dato, 3) : 0;
                                                $down[$hora] = ($dato = ($elemento['down'][$hora] - $downAnterior)*8/1024/1024/(60*$gapMinuto)) > 0 ? round($dato, 3) : 0;
                                                $i = $gapMinuto;
                                        }
                                        $upAnterior = $elemento['up'][$hora];
                                        $downAnterior = $elemento['down'][$hora];
                                }
                                else   
                                {
                                        unset($elemento['up'][$hora]);
                                        unset($elemento['down'][$hora]);
                                }
                                $i--;
                        }
                        $salida[$cliente]['up'] = $up;
                        $salida[$cliente]['down'] = $down;
                }
                $file = fopen('../storage/Crons/' . $ayer . '-sem.dat', 'w');
                fwrite($file, json_encode($salida));
                fclose($file);
        }
        else {
                return 'Error al abrir el archivo storage/Crons/' . $ayer . '.dat';
        }
        return 'true';
    }

    public static function readDay()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $archivo = date('Ymd') . '.dat';
        $hora = date('H.i');
        $gateways = self::getGateways();
        foreach ($gateways as $mikrotik)
        {
            $gateway = Panel::find($mikrotik);
            dd($archivo);
            $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
            if ($apiMikro) 
            {
                $allData = $apiMikro->getGatewayData();
                foreach ($allData['hotspotHost'] as $elemento)
                {
                        if ($elemento['authorized'] == 'false') {
                                $apiMikro->removeClientBloqued ($elemento['.id']);
                        }
                        if (isset($elemento['comment']) && is_numeric($elemento['comment'])) 
                        {
                                File::append(
                                        storage_path('Crons/' . $archivo),
                                        $elemento['comment'] . ';' . $hora . ';' . $elemento['bytes-in'] . ';' . $elemento['bytes-out'] . PHP_EOL
                                );
                        }
                }
                unset($apiMikro);
            }
        }
    }

    private static function getGateways ()
    {
        $planes = Plan::select('gateway_id')->where('gateway_id', '!=', null)->get();
        $gateways = null;
        foreach ($planes as $plan)
        {
            if ($gateways === null)
            {
                $gateways[] = $plan->gateway_id;
            }
            else
                {
                    $existe = false;
                    foreach ($gateways as $gateway)
                    {
                        if ($gateway == $plan->gateway_id)
                        {
                            $existe = true;
                        }
                    }
                    if (!$existe)
                    {
                        $gateways[] = $plan->gateway_id;
                    }

                }
        }
        return $gateways;
    }

    public static function getWeek($contrato_id = 2)
    {
        for ($day=7; $day > 0; $day--)
            {
                date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
                $dayTarget = date('Ymd', strtotime(date('Ymd').'- ' . $day . ' days'));
                $fecha = date('D', strtotime(date('Ymd').'- ' . $day . ' days'));
                if (file_exists('../storage/Crons/' . $dayTarget . '-sem.dat'))
                {
                        $file = fopen('../storage/Crons/' . $dayTarget . '-sem.dat', 'r');
                        $allData = json_decode(fgets($file), true);
                        if (isset($allData[$contrato_id]))
                        {
                                foreach ($allData[$contrato_id]['down'] as $key => $value)
                                {
                                        $salida['labels'][] = $fecha;
                                        $salida['up'][] = $allData[$contrato_id]['up'][$key];
                                        $salida['down'][] = $allData[$contrato_id]['down'][$key];
                                }
                        }
                        fclose($file);
                }
                else
                {
                        dd('Error no encuentra archivo: ' . $dayTarget);
                }
            }
            return($salida);
    }

    public static function buscarProveedoresCaidos ()
    {
            $proveedores = Proveedor::where('estado', true)->get();
            foreach ($proveedores as $proveedor)
            {
                if (!$proveedor->enLineaSigueIgual())
                {
                        $toSend = new CambioDeEstadoEnProveedor($proveedor);
                        $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.CAMBIO_ESTADO_PROVEEDOR_MAIL_GROUP'));
                        Mail::to($arrayCorreos)->send($toSend);
                }
            }
    }
    
    public static function enviarMailDeudasPendientes ()
    {
        $deudas = Site_has_incidente::where('tipo', 'DEUDA TECNICA')->where('final', null)->orderByDesc('prioridad')->get();
        //dd($deudas);
        $toSend = new DeudaTecnicaResumen($deudas);
        $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.DEUDAS_TECNICA_MAIL_GROUP'));
        Mail::to($arrayCorreos)->send($toSend);
    }

    public static function borrarArchivos()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $paraBorrar = date('Ymd', strtotime(date('Ymd')."- 7 days"));
        $archivos = Storage::disk('crons')->files();
        foreach ($archivos as $archivo) {
                if ((str_split($archivo,8)[0]) < $paraBorrar)
                {
                        //echo 'borrar ' . $archivo . '<br>';
                        Storage::disk('crons')->delete($archivo);
                }
        }
        return true;
    }
}