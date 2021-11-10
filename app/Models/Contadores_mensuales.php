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
                        $this->ene = $data;
                        break;
                case '02':
                        $this->feb = $data;
                        break;
                case '03':
                        $this->mar = $data;
                        break;
                case '04':
                        $this->abr = $data;
                        break;
                case '05':
                        $this->may = $data;
                        break;
                case '06':
                        $this->jun = $data;
                        break;
                case '07':
                        $this->jul = $data;
                        break;
                case '08':
                        $this->ago = $data;
                        break;
                case '09':
                        $this->sep = $data;
                        break;
                case '10':
                        $this->oct = $data;
                        break;
                case '11':
                        $this->nov = $data;
                        break;
                case '12':
                        $this->dic = $data;
                        break;
                default:
                        echo 'error en contadores_mensual';
                        break;
        }
    }
    public function imprimirActual()
    {
        switch ($this->ultimo_mes) 
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
    }
}
