<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use App\Custom\GatewayMikrotik;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\Contrato;

class InstasChart extends BaseChart
{
    /**
     * Determines the chart name to be used on the
     * route. If null, the name will be a snake_case
     * version of the class name.
     */
    public ?string $name = 'insta_name';

    /**
     * Determines the name suffix of the chart route.
     * This will also be used to get the chart URL
     * from the blade directrive. If null, the chart
     * name will be used.
     */
    public ?string $routeName = 'insta';

    /**
     * Determines the prefix that will be used by the chart
     * endpoint.
     */
    public ?string $prefix = 'some_prefix';
    
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $contrato = Contrato::find($request->header('cliente'));
        $mac_address = $contrato->relEquipo->mac_address;
        $path_completo = '../storage/instas/' . $contrato_id . '.dat';
        ($status_chart = $request->header('status-chart') ? true :false);
        if ($this->getDataFromGateway($status_chart, $contrato, $path_completo, $mac_address)) {
                ($rta = $this->getInstaArrays($contrato->id, $path_completo));
                return Chartisan::build()
                ->labels($rta['labels'])
                ->dataset($rta['dataset1']['name'], $rta['dataset1']['values'])
                ->dataset($rta['dataset2']['name'], $rta['dataset2']['values']);
        }else {
                return 'Error al leer datos en el Gateway.';
        }
    }

    private function getDataFromFile ($contrato_id, $path_completo) {
        if (File::exists($path_completo)){
                $file = fopen($path_completo, 'r');
                while(!feof($file))
                {
                        if (gettype($dato=fgets($file)) == 'string') {
                                $capturas[] = explode(';', trim($dato));
                        }
                }
                fclose($file);
                return $capturas;
        }
        return false;
    }
    private function getInstaArrays ($contrato_id, $path_completo) {
        $unidad = 'Bits';
        $biggest_capture = 0;
        $divisor = 1;
        $capturas_totales = 41;
        if ($capturas = $this->getDataFromFile($contrato_id, $path_completo)){
                ## Borro archivo
                (File::delete($path_completo));
                $can_capturas = (count($capturas));
                for ($i = ( ($can_capturas - $capturas_totales) < 0 ? 0 : $can_capturas - $capturas_totales) ; $i < $can_capturas; $i++) {
                        $captura_date = explode('.', $capturas[$i][0]);
                        if (isset($capturas[$i-1])){
                                $rta['labels'][] = $captura_date[1] . ':' . $captura_date[2] . ':' . $captura_date[3] ;
                                $prev_captura_date = explode('.', $capturas[$i-1][0]);
                                $sec_actual = ($prev_captura_date[2] === $captura_date[2]) ? $captura_date[3] : ($captura_date[3] + 60);
                                $delta_secs = $sec_actual - $prev_captura_date[3];
                                $temp_biggest_capture = $rta['dataset1']['values'][] = ( - $capturas[$i-1][2] + $capturas[$i][2]) *8/$delta_secs;
                                $rta['dataset2']['values'][] = ( - $capturas[$i-1][1] + $capturas[$i][1]) *8/$delta_secs;
                                if ($temp_biggest_capture > $biggest_capture) {
                                        $biggest_capture = $temp_biggest_capture;
                                }
                        }/* else {
                                $rta['dataset1']['values'][] = $capturas[$i][2];
                                $rta['dataset2']['values'][] = $capturas[$i][1];
                        } */
                        //echo $capturas[$i][0] . ';' . $capturas[$i][1] . ';' . $capturas[$i][2] . '<br>';
                        File::append(
                                storage_path($path_completo),
                                $capturas[$i][0] . ';' . $capturas[$i][1] . ';' . $capturas[$i][2] . PHP_EOL
                        );
                }
                if ($biggest_capture > 1024) {
                        $divisor = 1024;
                        $unidad = 'Kb';
                        if (($biggest_capture/$divisor) > 1024) {
                                $divisor *= 1024;
                                $unidad = 'Mb';
                        }
                }
                if ($divisor > 1) {
                        for ($i=0; $i < count($rta['dataset1']['values']); $i++) { 
                               if ($i == (count($rta['dataset1']['values'])-1)) {
                                       unset($rta['labels'][$i]);
                                       unset($rta['dataset1']['values'][$i]);
                                       unset($rta['dataset2']['values'][$i]);
                               }else {
                               $rta['dataset1']['values'][$i] = $rta['dataset1']['values'][$i] / $divisor;
                               $rta['dataset2']['values'][$i] = $rta['dataset2']['values'][$i] / $divisor;
                               }
                        }
                }
                $rta['dataset1']['name'] = 'Bajada en: ' . $unidad;
                $rta['dataset2']['name'] = 'Subida en: ' . $unidad;
                return($rta);
        }else {
                return('error al abrir el archivo');
        }
        
    }

    private function getDataFromGateway ($status, $contrato, $path_completo, $mac_address) {
        $hayOtraSesion = $this->checkIsAntherSesion($contrato->id, $path_completo);
        if ($hayOtraSesion) {
                return true;
        }else {
                if ($status){
                        (File::delete('../storage/instas/' . $contrato->id . '.dat'));
                }
                $apiMikro = GatewayMikrotik::getConnection($contrato->relPlan->relPanel->relEquipo->ip, $contrato->relPlan->relPanel->relEquipo->getUsuario(), $contrato->relPlan->relPanel->relEquipo->getPassword());
                if ($apiMikro){
                        $allData = $apiMikro->getGatewayData(true);
                        unset($apiMikro);
                        foreach ($allData as $key => $value) {
                                if ( $value['mac-address'] == $mac_address) {
                                        $hora = date('Ymd.H.i.s');
                                        if(
                                        File::append(
                                                storage_path($path_completo),
                                                $hora . ';' . $value['bytes-in'] . ';' . $value['bytes-out'] . PHP_EOL)) {
                                                        
                                                        return true;
                                                }else {return false;}
                                }
                        }
                } else {return false;}
        }
    }

    private function checkIsAntherSesion ($contrato_id, $path_completo) {
        $year = date('Ymd');
        $hour = date('H');
        $minute = date('i');
        $second = date('s');
        $capturas = $this->getDataFromFile ($contrato_id, $path_completo);
        if (!$capturas) {return false;}
        $ult_captura = count($capturas) -1;
        $ult_date = explode('.', $capturas[$ult_captura][0]);
        if ($year === $ult_date[0] && $hour === $ult_date[1] && $minute === $ult_date[2] && ($second - $ult_date[3]) < 5 ) {
                return true;
        }else {
                return false;
        }
    }
}
