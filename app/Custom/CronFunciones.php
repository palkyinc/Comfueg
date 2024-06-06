<?php

namespace App\Custom;

use DateTime;
use Illuminate\Support\Facades\Config;
use App\Models\Panel;
use App\Models\Issue;
use App\Models\Issues_update;
use App\Models\Contrato;
use App\Models\Mac_address_exception;
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
use App\Custom\ubiquiti;
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
    private static function setClockAndResetGateway ()
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
    private static function readCounterGateway()
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
                        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'readCounterGateway', 'error' => 'ERROR al contactar al Gateway: ' . $gateway->relEquipo->ip]);
                }
        }
    }
    private static function resetCounter($mensual = false)
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
            } else {
                    self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'resetCounter()', 'error' => 'ERROR al contactar al Gateway: ' . $gateway->relEquipo->ip]);
            }
        }
    }
    private static function generarArchivoSem($dias = 1)
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $ayer = date('Ymd', strtotime(date('Ymd')."- $dias days"));
        if (file_exists('/app/storage/Crons/' . $ayer . '.dat'))
        {
                $file = fopen('/app/storage/Crons/' . $ayer . '.dat', 'r');
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
                $file = fopen('/app/storage/Crons/' . $ayer . '-sem.dat', 'w');
                fwrite($file, json_encode($salida));
                fclose($file);
                return true;
        }
        self::logError(['clase' => 'Cronfunciones.php', 'metodo' => 'generarArchivoSem', 'error' => 'ERROR al abrir el archivo storage/Crons/' . $ayer . '.dat']);
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
    private static function actualizarIssuesVencidos ()
    {
        $issues = Issue::where('closed', false)->get();
        foreach ($issues as $key => $issue)
        {
                if ($issue->getVencida(true))
                {
                        $issue_updates = Issues_update::where('issue_id', $issue->id)->get();
                        $indice = count($issue_updates)-1;
                        if ($indice > -1) {
                                $last_update = ($issue_updates[$indice]);
                                $last_update_date = new DateTime($last_update->created_at);
                                if ($last_update->relUsuario->id !== 1) {
                                        $modify = '+5 day';
                                } 
                                else 
                                {
                                        $modify = '+2 day';
                                }
                                $last_update_date->modify($modify);
                                if ($last_update_date->format('w') == 0) {
                                        $last_update_date->modify('+1 day');
                                } elseif ($last_update_date->format('w') == 6) {
                                        $last_update_date->modify('+2 day');
                                }
                                $hoy =  new DateTime();
                                $interval = $last_update_date->diff($hoy);
                                if (!$interval->invert && $last_update->relUsuario->id !== 1 && $issue->titulo_id !== 5) ### ADVERTENCIA
                                {
                                        ### Advertencia de Ticket
                                        self::enviarAdverCerrar($issue,
                                                                'Aviso automático. Ticket vencido y sin novedades. De no mediar novedades se cerrará automaticamente en 2 días.',
                                                                5,
                                                                2);
                                }
                                elseif (!$interval->invert && ($last_update->relUsuario->id === 1 || $issue->titulo_id === 5)) ### CERRAR
                                {
                                        ### Cerrar Ticket
                                        if ($issue->titulo_id !== 5) {
                                                self::enviarAdverCerrar($issue,
                                                                        'Aviso automático. Ticket vencido y sin novedades. Se cierra.',
                                                                        5,
                                                                        3);
                                        } else {
                                                 self::bajaIssue($issue);
                                        }
                                        
                                } else {
                                        ### Ticket sin vencer
                                }
                        }
                        else
                        {
                                ### Advertencia de ticket
                                self::enviarAdverCerrar($issue,
                                                                'Aviso automático. Ticket vencido y sin novedades. De no mediar novedades se cerrará automaticamente en 2 días.',
                                                                5,
                                                                2);
                        }
                } else {
                                ### Ticket sin vencer
                                ### Suspension por Mora -> suspender
                                /* self::logError(['clase' => 'Cronfunciones.php',
                                                'metodo' => 'actualizarIssuesVencidos',
                                                'error' => 'Ticket sin Vencer:' . $issue->id]); */
                }
        }
    }
    private static function enviarAdverCerrar($issue, $texto, $grupoMail, $tipoMail)
    {
        $arrayIds = Mail_group::arrayUsersId($grupoMail);
        $issue_updates_new = new Issues_update();
        $issue_updates_new->issue_id = $issue->id;
        $issue_updates_new->descripcion = $texto;
        $issue_updates_new->usuario_id = 1;
        $issue_updates_new->asignadoAnt_id = $issue->asignado_id;
        $issue_updates_new->asignadoSig_id = $issue->asignado_id;
        $issue_updates_new->save();
        $viewers = json_decode($issue->viewers);
        foreach ($arrayIds as $key => $arrayid) {
                if(!$viewers || ($viewers && !in_array($arrayid, $viewers)))
                {
                        $viewers[] = $arrayid; 
                }
        }
        $issue->viewers = json_encode($viewers);
        if($tipoMail === 3)
        {
                $issue->closed = true;
        }
        $issue->save(); 
        $issue->enviarMail($tipoMail, true);
    }
    private static function enviarErrorsMail()
    {
        $grupoMail = 4;
        if(file_exists('/app/storage/logs/Errors.log'))
        {
                $file = fopen('/app/storage/logs/Errors.log', 'r');
                while (!feof($file))
                {
                        $linea = explode(';', fgets($file));
                        if(count($linea) === 4)
                        {
                                $errores [] = $linea;
                        }
                }
                fclose($file);
                if(isset($errores))
                {
                        $arrayCorreos = Mail_group::arrayCorreos($grupoMail);
                        $toSend = new ReporteError($errores);
                        Mail::to($arrayCorreos)->send($toSend);
                        Storage::disk('logs')->delete('Errors.log');
                }
        }
    }
    private static function borrarArchivos()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $paraBorrar = date('Ymd', strtotime(date('Ymd')."- 7 days"));
        $archivos = Storage::disk('crons')->files();
        foreach ($archivos as $archivo) {
                if ((str_split($archivo,8)[0]) < $paraBorrar)
                {
                        Storage::disk('crons')->delete($archivo);
                }
        }
    }
    public static function logError($data)
    {
        ### $data['clase']
        ### $data['metodo']
        ### $data['error']
        if($file = fopen('/app/storage/logs/Errors.log', 'a+'))
        {
                fwrite($file, date('Y-m-d|H:s') . ';' . $data['clase'] . ';' . $data['metodo'] . ';' . $data['error'] . PHP_EOL);
                fclose($file);
        }
    }
    public static function audoriaPaneles()
    {
        $exceptions = Mac_address_exception::get();
        $contratos = Contrato::select('id', 'num_panel', 'num_equipo')->where('baja', false)->get();
        $paneles = Panel::select('id', 'ssid', 'id_equipo')->where('activo', true)->where('rol', 'PANEL')->get();
        foreach ($paneles as $key => $panel)
        {
                $File = fopen ('configPanels/' . $panel->relEquipo->ip . '-bkp.cfg', 'r');
                while (!feof ($File))
                {
                        $linea = fgets($File);
                        $datoLinea = explode('=', $linea);
                        $lineaExplotada = explode ('.', ($datoLinea[0]));
                        if ($lineaExplotada[0] == 'wireless' && $lineaExplotada[1] == '1' && $lineaExplotada[2] == 'mac_acl' && isset($lineaExplotada[4]))
                        {
                                
                                if ($lineaExplotada[4] === 'comment') {
                                        $dato = explode (';', $datoLinea[1]);
                                        $macs_panel[$lineaExplotada[3]]['contrato_id'] = $dato[0];
                                        if (count($dato) > 1) {
                                                $macs_panel[$lineaExplotada[3]]['tipo'] = $dato[1];
                                        }else{
                                                $macs_panel[$lineaExplotada[3]]['tipo'] = 'Unknown';
                                        }
                                }
                                if ($lineaExplotada[4] === 'mac') {
                                        $macs_panel[$lineaExplotada[3]]['mac'] = rtrim($datoLinea[1]);
                                        $macs_panel[$lineaExplotada[3]]['panel'] = $panel->id;
                                }
                        }
                }
                fclose($File);
                foreach ($macs_panel as $key => $value)
                {
                        
                        if (is_numeric($value['contrato_id']))
                        {
                                $encontrado = false;
                                foreach ($contratos as $key => $contrato) {
                                        if ($contrato->id == $value['contrato_id'] && $contrato->num_panel == $value['panel'] && $contrato->relEquipo->mac_address === $value['mac']) {
                                                //dd($macs_panel);                                                
                                                //dd($value);                                                
                                                $encontrado = true;
                                        }
                                }
                                if (!$encontrado) {
                                        $noEncontrados [] = $value;
                                        //echo $value['contrato_id'] . ' | ' . $value['mac'] . ' | ' . $value['panel'] . '<br>';
                                }
                        }
                        else 
                        {
                                $noEncontrados [] = $value;
                                //echo $value['contrato_id'] . ' | ' . $value['mac'] . ' | ' . $value['panel'] . '<br>';
                        }
                }
        }
        foreach ($noEncontrados as $key => $noEncontrado)
        {
                $existe = false;
                if(!isset($noEncontradosTemp))
                {
                        $noEncontradosTemp[] = $noEncontrado;
                } else {
                        foreach ($noEncontradosTemp as $aguja => $value) {
                                if($noEncontrado['mac'] === $value['mac'])
                                {
                                        $existe = true;
                                }
                        }
                        if(!$existe) {
                                $noEncontradosTemp[] = $noEncontrado;
                        }
                }
        }
        $noEncontrados = $noEncontradosTemp;
        foreach ($noEncontrados as $keyNoEncontrado => $noEncontrado)
        {
                if($noEncontrado['contrato_id'] === 'exception')
                {
                        foreach ($exceptions as $keyException => $exception) {
                                if ($exception->panel_id === $noEncontrado['panel'] && $exception->relEquipo->mac_address === $noEncontrado['mac']) {
                                        unset($noEncontrados[$keyNoEncontrado]);
                                }
                        }
                }
        }
        foreach ($noEncontrados as $key => $value) {
                $data = 'Contrato: ' . $value['contrato_id'] . '| Tipo: ' . $value['tipo'] . '| Mac: ' . $value['mac'] . '| Panel: ' . $value['panel'];
                self::logError(['clase' => 'Cronfunciones.php',
                                'metodo' => 'audoriaPaneles',
                                'error' => $data
                        ]);
        }
    }
    public static function bkpPaneles()
    {
        $paneles = Panel::select('id', 'ssid', 'id_equipo')->where('activo', true)->where('rol', 'PANEL')->get();
        self::makeBkpArray($paneles);
        $ptpap = Panel::where('activo', true)->where('rol', 'PTPAP')->get();
        self::makeBkpArray($ptpap);
        $ptpst = Panel::where('activo', true)->where('rol', 'PTPST')->get();
        self::makeBkpArray($ptpst);
    }
    private static function makeBkpArray ($paneles)
    {
        foreach ($paneles as $key => $panel) {
                $result = self::makeBkp ($panel->relEquipo->getUsuario(), $panel->relEquipo->getPassword(), $panel->relEquipo->ip);
                if($result['retval'])
                {
                        foreach ($result['output'] as $key => $value) {
                        self::logError(['clase' => 'Cronfunciones.php',
                                                        'metodo' => 'makeBkpArray',
                                                        'error' => 'IP: ' . $panel->relEquipo->ip . ' | ' . $value]);    
                        }
                }
        }
    }
    private static function makeBkp ($usuario, $password, $ip)
    {
        return ubiquiti::makeBkp([
                         'usuario' => $usuario,
                         'password' => $password,
                         'ip' => $ip,
                        ]);
    }
    public static function bajaAut()
    {
        $issues = Issue::where('closed', false)->where('titulo_id', 1)->get();
        foreach ($issues as $key => $issue) {
                self::bajaIssue($issue);
        }
    }
    private static function bajaIssue(Issue $issue)
    {
        $respuestas = $issue->relContrato->removeContract();
                $issue->relContrato->refresh();
                $error_rta = false;
                foreach ($respuestas as $key => $rta)
                {
                        if ($wordTest = str_split($rta, 5)[0] === 'ERROR')
                        {
                                self::logError(['clase' => 'Cronfunciones.php',
                                                        'metodo' => 'bajaAut',
                                                        'error' => $rta]);
                                $error_rta = true;
                        }
                }
                switch ($issue->titulo_id) {
                        case 5:
                                $mensaje = 'Baja de contrato por exceder los 30 días de suspensión por mora.';
                                break;
                        
                        default:
                                $mensaje = 'Baja automática en el dia de la fecha. Se cierra.';
                                break;
                }
                if (!$error_rta)
                {
                        self::enviarAdverCerrar($issue,
                                        $mensaje,
                                        6,
                                        3);
                
                }
    }
    public static function diario()
    {
        self::resetCounter();
        self::readCounterGateway();
        self::resetCounter(true);
        self::generarArchivoSem();
        self::borrarArchivos();
    }
    public static function diario01()
    {
        self::setClockAndResetGateway();
    }
    public static function diario02()
    {
        self::actualizarIssuesVencidos();
    }
    public static function diario03()
    {
        self::enviarErrorsMail();
    }
}