<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class SampleChart extends BaseChart
{
    /**
     * Determines the chart name to be used on the
     * route. If null, the name will be a snake_case
     * version of the class name.
     */
    public ?string $name = 'twentyFour_name';

    /**
     * Determines the name suffix of the chart route.
     * This will also be used to get the chart URL
     * from the blade directrive. If null, the chart
     * name will be used.
     */
    public ?string $routeName = 'twentyFour';

    /**
     * Determines the prefix that will be used by the chart
     * endpoint.
     */
    public ?string $prefix = 'some_prefix';

    /**
     * Determines the middlewares that will be applied
     * to the chart endpoint.
     * public ?array $middlewares = ['auth'];
     */
    
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request):Chartisan
    {
        $cliente = ($request->header('cliente'));
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $hora = date('H');
        $minuto = date('i');
        $hoy = date('Ymd') . '.dat';
        $ayer = date('Ymd', strtotime(date('Ymd')."- 1 days")) . '.dat';
        $labels = $this->getLabels($hora, $minuto);
        $ultimasTwentyFour = $this->getultimasTwentyFour($hoy, $ayer, $hora, $minuto, $labels, $cliente);
        return Chartisan::build()
            ->labels($labels)
            ->dataset('Download', $ultimasTwentyFour['down'])
            ->dataset('Upload', $ultimasTwentyFour['up'])
            ;
    }

    private function getDatosDelDia($dia, $datos, $cliente)
    {
        $file = fopen('../storage/Crons/' . $dia, 'r');
        $downAnterior = null;
        $upAnterior = null;
        while (!feof($file))
        {
            $linea = fgets($file);
            if ($linea)
            {
                $linea = explode(';', trim($linea));
                if ($linea[0] == $cliente)
                {
                    if (!$downAnterior && !$upAnterior)
                    {
                        $datos[$linea[1]]['up'] = 0;
                        $datos[$linea[1]]['down'] = 0;
                    }
                    else
                        {
                            $datos[$linea[1]]['up'] = ($dato = ($linea[2] - $upAnterior)*8/1024/1024/60) > 0 ? $dato : 0;
                            $datos[$linea[1]]['down'] = ($dato = ($linea[3] - $downAnterior)*8/1024/1024/60) > 0 ? $dato : 0;
                        }
                    $upAnterior = $linea[2];
                    $downAnterior = $linea[3];
                    
                }
            }
        }
        fclose($file);
        return $datos;
    }
    private function getUltimasTwentyFour($hoy, $ayer, $hora, $minuto, $labels, $cliente)
    {
        $datosArchivos = null;
        $datosArchivos = $this->getDatosdelDia($ayer, $datosArchivos, $cliente);
        $datosArchivos = $this->getDatosdelDia($hoy, $datosArchivos, $cliente);
        foreach ($labels as $label) {
            if (isset($datosArchivos[$label]))
            {
                $salida['up'][] = $datosArchivos[$label]['up'];
                $salida['down'][] = $datosArchivos[$label]['down'];
            }
            else
                {
                    $salida['up'][] = 0;
                    $salida['down'][] = 0;
                }
        }
        return ($salida);
    }

    private function getLabels($hora, $minuto)
    {
        $labels [] = $hora . '.' . $minuto;
        $minutoLabel = $minuto;
        $minutoLabel ++;
        $horaLabel = $hora;
        while ($horaLabel != $hora || $minutoLabel != $minuto)
        {
            if ($minutoLabel == 60)
            {
                $minutoLabel = 0;
                $horaLabel ++;
                if ($horaLabel == 24)
                {
                    $horaLabel = 0;
                }
            }
            $labels[] = str_pad(strval($horaLabel), 2, '0', STR_PAD_LEFT) . '.' . str_pad(strval($minutoLabel), 2, '0', STR_PAD_LEFT);
            $minutoLabel ++;
        }
        return $labels;
    }
}