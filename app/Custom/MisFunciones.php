<?php

namespace App\Custom;

use App\Models\Panel;

abstract class MisFunciones {

    public static function prueba()
    {
        return 'Hola';
    }
    public static function encontrarSitios ()
    {
        $id = 30;
        $afectado = Panel::find($id);
        if ($afectado->rol == 'PTPAP' || $afectado->rol == 'PTPST' || $afectado->rol == 'SWITCH') {
            if ($afectado->rol == 'PTPAP') {
                //encontrar el siguiente sitio (que es el afectado)
                $puntero = $afectado->id;
                $siguienteSitio = Panel::where('panel_ant', $puntero)->where('rol', 'PTPST')->first();
                while ($siguienteSitio) {
                    $sitioAfectado[] = $siguienteSitio->relSite->nombre;
                    $indirectos = Panel::where('num_site', $siguienteSitio->relSite->id)->where('rol', 'PANEL')->get();
                    foreach ($indirectos as $indirecto) {
                        $afectadosIndi[] = $indirecto->ssid;
                    }
                    $sigPuntero = Panel::where('rol', 'PTPAP')->where('num_site', $siguienteSitio->relSite->id)->first();
                    //dd($afectadosIndi);
                    if ($sigPuntero) {
                        $siguienteSitio = Panel::where('panel_ant', $sigPuntero->id)->where('rol', 'PTPST')->first();
                    } else {
                        $siguienteSitio = null;
                    }
                }
            } else {
                $sitioAfectado[] = $afectado->relsite->nombre;
            }
            dd($afectadosIndi);
        } elseif ($afectado->rol == 'PANEL') {
            echo 'Es Panel';
        }
    }
    public static function obenerSitiosPaneles()
    {
        $id = 31;
        $sitios = Site::all();
        foreach ($sitios as $sitio) {
            $ptpst = Panel::where('num_site', $sitio->id)->where('rol', 'PTPST')->first();
            if ($ptpst){
                $panel_ant = Panel::find($ptpst->panel_ant);
                $arbol[] = [$panel_ant->relSite->id => $ptpst->relSite->id, $panel_ant->relSite->nombre => $ptpst->relSite->nombre];
            }
        }
        $afectado = Panel::find($id);
        if ($afectado->rol == 'PANEL')
        {
            echo " Sitio Actual el unico afectado";
        } elseif ($afectado->rol == 'PTPST' || $afectado->rol == 'SWITCH')
            {
                echo "Sitio actual + siguientes (de esxistir) afectados";
            } else if ($afectado->rol == 'PTPAP')
                {
                    echo "Sitio siguiente/s afectados";
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

}