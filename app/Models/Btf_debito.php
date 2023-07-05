<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Btf_debito extends Model
{
    use HasFactory;

    public function relCliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }
    public function getSucursal () {
        switch ($this->sucursal) {
            case '02':
                return 'Ushuaia';
                break;
            case '03':
                return 'Río Grande';
                break;
            case '04':
                return 'Rí Gallegos';
                break;
            case '05':
                return 'Buenos Aires';
                break;
            case '22':
                return 'Kuanip';
                break;
            case '24':
                return 'El Calafate';
                break;
            case '33':
                return 'Chacra II';
                break;
            case '42':
                return 'Malvinas Argentinas';
                break;
            case '43':
                return 'Tolhuin';
                break;
            case '20':
                return 'Cuentas Sueldos';
                break;
            
            default:
                return 'ERROR';
                break;
        }
    }
    public function getTipoCuenta () {
        switch ($this->tipo_cuenta) {
            case '01':
                return 'Cuenta Corriente';
                break;
            case '03':
                return 'Caja de ahorro';
                break;
            
            default:
                return 'ERROR';
                break;
        }
    }
    public function getImporte ($centavos = false) {
        $importe = explode('.', $this->importe);
        return (!$centavos) ? $importe[0] : (isset($importe[1]) ? $importe[1] : '00');
    }
    public function scopeHabilitadas($query, $habilitadas)
    {
        if($habilitadas == 'on')
            return $query->where('desactivado', false);
    }
    public function scopeCliente($query, $cliente)
    {
        if($cliente)
            return $query->where('cliente_id', $cliente);
    }
}
