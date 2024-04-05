<?php

namespace App\Custom;
use Illuminate\Support\Facades\Config;
use App\Models\Panel;
use App\Models\Contrato;
use App\Models\Plan;
use App\Models\Proveedor;
use App\Models\Site_has_incidente;
use App\Models\Mail_group;
use App\Models\Contadores_mensuales;
use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use App\Mail\DeudaTecnicaResumen;
use App\Mail\ReporteError;
use App\Mail\CambioDeEstadoEnProveedor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;


abstract class CronFunciones
{

    public static function resetContadores_mensuales() 
    {
        $todosLosContadores = Contadores_mensuales::get();
        $mes_actual = date('m');
        switch ($mes_actual) {

                case '1':
                        $mes_actual = 'ene';
                        break;
                
                case '2':
                        $mes_actual = 'feb';
                        break;
                
                case '3':
                        $mes_actual = 'mar';
                        break;
                
                case '4':
                        $mes_actual = 'abr';
                        break;
                
                case '5':
                        $mes_actual = 'may';
                        break;
                
                case '6':
                        $mes_actual = 'jun';
                        break;
                
                case '7':
                        $mes_actual = 'jul';
                        break;
                
                case '8':
                        $mes_actual = 'ago';
                        break;
                
                case '9':
                        $mes_actual = 'sep';
                        break;
                
                case '10':
                        $mes_actual = 'oct';
                        break;
                
                case '11':
                        $mes_actual = 'nov';
                        break;
                
                case '12':
                        $mes_actual = 'dic';
                        break;
                
                default:
                        return false;
                        break;
        }
        foreach ($todosLosContadores as $contador) {
                $contador->$mes_actual = 0;
                $contador->save();
        }
    }
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
                //$apiMikro->resetGateway();
            }
        }
    }
    public static function fixContadoresMensuales()
    {
        $contador_mensuales = Contadores_mensuales::get();
                echo '<table>
                <tr>
                <th>Contrato</th>
                <th>Nov</th>
                <th>Dic</th>
                <th>Ene</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Abr</th>
                </tr>';
        foreach ($contador_mensuales as $cm) {
                if(!$cm->relContrato->baja)
                {
                        echo '<tr>';
                        echo '<td>' . $cm->contrato_id . '</td>';
                        echo '<td>' . round($cm->nov/1024/1024/1024, 1) . '</td>';
                        echo '<td>' . round($cm->dic/1024/1024/1024, 1) . '</td>';
                        echo '<td>' . round($cm->ene/1024/1024/1024/10, 1) . '</td>';
                        echo '<td>' . round($cm->feb/1024/1024/1024/10, 1) . '</td>';
                        echo '<td>' . round($cm->mar/1024/1024/1024/10, 1) . '</td>';
                        echo '<td>' . round($cm->abr/1024/1024/1024, 1) . '</td>';
                        echo '</tr>';
                }
        }
                echo '</table>';
    }
    public static function readCounterGateway()
    {
        $gateways = self::getGateways();
        //$gateways[] = 56;
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
                                        if (Contrato::find($elemento['comment']))
                                        {
                                                $contador_mensual->anio = date('Y');
                                                $contador_mensual->ultimo_mes = date('m');
                                                $contador_mensual->setMounthCounter($elemento['bytes-in'] + $elemento['bytes-out']);
                                                $contador_mensual->save();
                                        } else {
                                                self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'readCounterGateway', 'error' => 'Error: No existe el contrato:' . $elemento['comment'] . 'en el Gateway:' . $gateway->relEquipo->ip . 'al querer grabarlo como ContadorMensual.']);
                                        }
                                }
                        }
                } else {
                        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'readCounterGateway', 'error' => 'Error al contactar al Gateway' . $gateway->relEquipo->ip]);
                }
        }
        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'readCounterGateway', 'error' => 'Finaliza OK']);;
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
                        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'resetCounter(Mensual)', 'error' => 'Finaliza OK']);
                }
                else   {
                        $apiMikro->resetCounter();
                        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'resetCounter()', 'error' => 'Finaliza OK']);
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
                self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'generarArchivoSem', 'error' => 'FInaliza OK']);        
                return true;
        }
        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'generarArchivoSem', 'error' => 'Error al abrir el archivo storage/Crons/' . $ayer . '.dat']);
        return false;
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
            } else {
                self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'readDay', 'error' => 'Error al contactar al Gateway' . $gateway->relEquipo->ip]);
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
                        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'getWeek', 'error' => 'Error no encuentra archivo: /storage/Crons/' . $dayTarget . '-sem.dat']);
                        return false;
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
        $toSend = new DeudaTecnicaResumen($deudas);
        $arrayCorreos = Mail_group::arrayCorreos(Config::get('constants.DEUDAS_TECNICA_MAIL_GROUP'));
        Mail::to($arrayCorreos)->send($toSend);
    }
    public static function enviarErrorsMail()
    {
        if($file = fopen('../storage/logs/Errors.log', 'r'))
        {
                while (!feof($file))
                {
                        $linea = explode(';', fgets($file));
                        if(count($linea) === 4)
                        {
                                $errores [] = $linea;
                        }
                }
        }
        fclose($file);
        if($errores)
        {
                $grupoMail = 4;
                $arrayCorreos = Mail_group::arrayCorreos($grupoMail);
                $toSend = new ReporteError($errores);
                Mail::to($arrayCorreos)->send($toSend);
                Storage::disk('logs')->delete('Errors.log');
        }
    }
    public static function borrarArchivos()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $paraBorrar = date('Ymd', strtotime(date('Ymd')."- 7 days"));
        $archivos = Storage::disk('crons')->files();
        foreach ($archivos as $archivo) {
                if ((str_split($archivo,8)[0]) < $paraBorrar)
                {
                        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'borrarArchivos', 'error' => 'Se borra: ' . $archivo]);
                        Storage::disk('crons')->delete($archivo);
                }
        }
        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'borrarArchivos', 'error' => 'Finaliza OK']);
    }
    public static function logError($data)
    {
        ### $data['clase']
        ### $data['metodo']
        ### $data['error']
        if($file = fopen('../storage/logs/Errors.log', 'a+'))
        {
                fwrite($file, date('Y-m-d|H:s') . ';' . $data['clase'] . ';' . $data['metodo'] . ';' . $data['error'] . PHP_EOL);
                fclose($file);
        }else {
                echo '<p>ERROR al abrir el archivo ../storage/logs/Errors.log<p>';
        }
    }
    public static function diario()
    {
        self::resetCounter();
        self::readCounterGateway();
        self::resetCounter(true);
        self::generarArchivoSem();
        self::borrarArchivos();
        self::enviarErrorsMail();
    }
}