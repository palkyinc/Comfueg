<?php

namespace App\Custom;
use Illuminate\Support\Facades\Config;
use App\Models\Panel;
use App\Models\Plan;
use App\Models\Site_has_incidente;
use App\Models\Mail_group;
use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use App\Mail\DeudaTecnicaResumen;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;


abstract class CronFunciones
{

    public static function generarArchivoSem($dias = 1)
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $ayer = date('Ymd', strtotime(date('Ymd')."- $dias days"));
        if (file_exists('/inetpub/wwwroot/Comfueg/storage/Crons/' . $ayer . '.dat'))
        {
        $file = fopen('/inetpub/wwwroot/Comfueg/storage/Crons/' . $ayer . '.dat', 'r');
        while(!feof($file))
        {
                $linea = explode(';', trim(fgets($file)));
                if (isset($linea[2]))
                {
                        $salida[$linea[0]]['up'][$linea[1]] = $linea[2];
                        $salida[$linea[0]]['down'][$linea[1]] = $linea[3];
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
        $file = fopen('/inetpub/wwwroot/Comfueg/storage/Crons/' . $ayer . '-sem.dat', 'w');
        fwrite($file, json_encode($salida));
        fclose($file);
        }
        else {
                return false;
        }
        return true;
    }

    public static function readDay()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $archivo = date('Ymd') . '.dat';
        $archivoMes = date('Ym') . '-totMes.dat';
        $hora = date('H.i');
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
                ### leer el array -> convertor de json a array
                if (file_exists('/inetpub/wwwroot/Comfueg/storage/Crons/' . $archivoMes))
                {
                        $file = fopen('/inetpub/wwwroot/Comfueg/storage/Crons/' . $archivoMes, 'r');
                        $arrayTotMes = json_decode(fgets($file), true);
                        fclose($file);
                }
                foreach ($allData['hotspotUser'] as $elemento)
                {
                        if (isset($elemento['comment']) && is_numeric($elemento['comment'])) 
                        {
                                $arrayTotMes[$elemento['comment']] = $elemento['bytes-in'] + $elemento['bytes-out'];
                                
                        }
                }
                $file = fopen('/inetpub/wwwroot/Comfueg/storage/Crons/' . $archivoMes, 'w');
                fwrite($file, json_encode($arrayTotMes));
                fclose($file);
                foreach ($allData['hotspotHost'] as $elemento)
                {
                    if (isset($elemento['comment']) && is_numeric($elemento['comment'])) 
                    {
                        File::append(
                                storage_path('Crons/' . $archivo),
                                $elemento['comment'] . ';' . $hora . ';' . $elemento['bytes-in'] . ';' . $elemento['bytes-out'] . PHP_EOL
                            );
                    }
                }
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
                if (file_exists('/inetpub/wwwroot/Comfueg/storage/Crons/' . $dayTarget . '-sem.dat'))
                {
                        $file = fopen('/inetpub/wwwroot/Comfueg/storage/Crons/' . $dayTarget . '-sem.dat', 'r');
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
                        else
                        {
                                ### selft::simularDia()                
                        }
                        fclose($file);
                }
                else
                {
                        dd($fecha);
                
                        for ($h=0; $h < 24; $h++)
                        { 
                                for ($m=0; $m < 60; $m++)
                                {
                                }
                        } 
                }
            }
            return($salida);
    }
    
    public static function enviarMailDeudasPendientes ()
    {
        $deudas = Site_has_incidente::where('tipo', 'DEUDA TECNICA')->where('final', null)->get();
        //dd($deudas);
        $toSend = new DeudaTecnicaResumen($deudas);
        $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.DEUDAS_TECNICA_MAIL_GROUP'));
        Mail::to($arrayCorreos)->send($toSend);
    }

    public static function borrarArchivos()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $paraBorrar = date('Ymd', strtotime(date('Ymd')."- 9 days"));
        if (file_exists('/inetpub/wwwroot/Comfueg/storage/Crons/' . $paraBorrar . '-sem.dat'))
        {
                unlink('/inetpub/wwwroot/Comfueg/storage/Crons/' . $paraBorrar . '-sem.dat');
        }
    }
}