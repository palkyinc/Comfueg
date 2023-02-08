<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contadores_mensuales extends Model
{
    use HasFactory;
    protected $table = 'contadores_mensuales';

    public function setMounthCounter($data)
    {
        switch ($this->ultimo_mes) 
        {
                case '01':
                        $this->ene += $data;
                        break;
                case '02':
                        $this->feb += $data;
                        break;
                case '03':
                        $this->mar += $data;
                        break;
                case '04':
                        $this->abr += $data;
                        break;
                case '05':
                        $this->may += $data;
                        break;
                case '06':
                        $this->jun += $data;
                        break;
                case '07':
                        $this->jul += $data;
                        break;
                case '08':
                        $this->ago += $data;
                        break;
                case '09':
                        $this->sep += $data;
                        break;
                case '10':
                        $this->oct += $data;
                        break;
                case '11':
                        $this->nov += $data;
                        break;
                case '12':
                        $this->dic += $data;
                        break;
                default:
                        echo 'error en contadores_mensual';
                        break;
        }
    }
    public function imprimirActual($mes = false)
    {
        if (!$mes) {
                $mes = $this->ultimo_mes;
        }
        switch ($mes)
        {
                case '01':
                        return $this->calculateAmount($this->ene);
                        break;
                case '02':
                        return $this->calculateAmount($this->feb);
                        break;
                case '03':
                        return $this->calculateAmount($this->mar);
                        break;
                case '04':
                        return $this->calculateAmount($this->abr);
                        break;
                case '05':
                        return $this->calculateAmount($this->may);
                        break;
                case '06':
                        return $this->calculateAmount($this->jun);
                        break;
                case '07':
                        return $this->calculateAmount($this->jul);
                        break;
                case '08':
                        return $this->calculateAmount($this->ago);
                        break;
                case '09':
                        return $this->calculateAmount($this->sep);
                        break;
                case '10':
                        return $this->calculateAmount($this->oct);
                        break;
                case '11':
                        return $this->calculateAmount($this->nov);
                        break;
                case '12':
                        return $this->calculateAmount($this->dic);
                        break;
                default:
                        echo 'error en contadores_mensual';
                        break;
        }
    }
    public function getAmountMounth($mes)
    {
        switch ($mes)
        {
                case '01':
                        return ($this->ene/1024/1024/1024);
                        break;
                case '02':
                        return ($this->feb/1024/1024/1024);
                        break;
                case '03':
                        return ($this->mar/1024/1024/1024);
                        break;
                case '04':
                        return ($this->abr/1024/1024/1024);
                        break;
                case '05':
                        return ($this->may/1024/1024/1024);
                        break;
                case '06':
                        return ($this->jun/1024/1024/1024);
                        break;
                case '07':
                        return ($this->jul/1024/1024/1024);
                        break;
                case '08':
                        return ($this->ago/1024/1024/1024);
                        break;
                case '09':
                        return ($this->sep/1024/1024/1024);
                        break;
                case '10':
                        return ($this->oct/1024/1024/1024);
                        break;
                case '11':
                        return ($this->nov/1024/1024/1024);
                        break;
                case '12':
                        return ($this->dic/1024/1024/1024);
                        break;
                default:
                        echo 'error en contadores_mensual';
                        break;
        }
    }
    private function calculateAmount ($data)
    {
        if ($data < 1000)
        {
                return $data . 'B';
        }
        elseif ( ($data = intval($data/1024)) < 1000 )
                {
                        return $data . 'KB';
                }
                elseif ( ($data = intval($data/1024)) < 1000 )
                        {
                                return $data . 'MB';
                        }
                        elseif ( ($data = intval($data/1024)) < 1000 )
                                {
                                        return $data . 'GB';
                                }
                                elseif ( ($data = intval($data/1024)) < 1000 )
                                        {
                                                return $data . 'TB';
                                        }
    }
    public function getConteoData ()
    {
        $ultimo_mes = $this->ultimo_mes + 1;
        if ($ultimo_mes === 13) {
                $ultimo_mes = 1;
        }
        $meses = [1 => 'ene',2 => 'feb',3 => 'mar',4 => 'abr',5 => 'may',6 => 'jun',7 => 'jul',8 => 'ago',9 => 'sep',10 => 'oct',11 => 'nov',12 => 'dic',];
        for ($i=0; $i < 12; $i++) { 
                $labels[$i] = $meses[$ultimo_mes];
                $values[$i] = $this->getAmountMounth($ultimo_mes) ?? 0;
                $ultimo_mes++;
                if ($ultimo_mes > 12) {
                        $ultimo_mes = 1;
                }
        }
        return(['labels' => $labels, 'values' => $values]);
    }
}
