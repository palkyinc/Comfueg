<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Custom\GatewayMikrotik;

class Plan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'planes';

    public function relPanel ()
    {
        return $this->belongsTo('App\Models\Panel', 'gateway_id', 'id');
    }

    public function modifyMikrotik ($array)
    {
        if (isset($array['gateway']))
        {
            //SI:cambio nombre
            //buscar plan en gateway viejo con nombre viejo y borralo
            //ELSE: buscar plan en gateway viejo con nombre y borrarlo
            if ($this->getOriginal()['gateway_id'])
            {
                $nombre = isset($array['nombre']) ? $this->getOriginal()['nombre'] : $this->nombre;
                $panelAnt = Panel::find($this->getOriginal()['gateway_id']);
                $apiMikro = GatewayMikrotik::getConnection($panelAnt->relEquipo->ip, $panelAnt->relEquipo->getUsuario(), $panelAnt->relEquipo->getPassword());
                if ($apiMikro)
                {
                    $apiMikro->checkTotales();
                    $answerType = $apiMikro->getTypeNumbers($this->id);
                    $answerTree = $apiMikro->getTreeNumbers($this->id);
                    $answerMangle = $apiMikro->getMangleNumbers($this->id);
                    $apiMikro->modificarPlanType(['numbers' => $answerType['down']], ['numbers' => $answerType['up']], 'remove');
                    $apiMikro->modificarPlanTree(['numbers' => $answerTree['down']], ['numbers' => $answerTree['up']], 'remove');
                    $apiMikro->modificarPlanMangle(['numbers' => $answerMangle['down']], ['numbers' => $answerMangle['up']], 'remove');
                    // si hay un gateway_id nuevo crear los planes.
                    unset($apiMikro);
                }else return false;
            }
            if ($this->gateway_id)
            {
                $apiMikro = GatewayMikrotik::getConnection($this->relPanel->relEquipo->ip, $this->relPanel->relEquipo->getUsuario(), $this->relPanel->relEquipo->getPassword());
                if ($apiMikro)
                {
                    $apiMikro->checkTotales();
                    // cargar PLan en gateway nuevo
                    $apiMikro->crearPlanType($this->id, $this->subida, $this->bajada);
                    $apiMikro->crearPlanTree($this->id, $this->id);
                    $apiMikro->crearPlanMangle($this->id, $this->id);
                    unset($apiMikro);
                } else return false;
            }
            return true;
        }
        elseif ((isset($array['bajada']) || isset($array['subida'])) && $this->gateway_id)
            {
                    $apiMikro = GatewayMikrotik::getConnection( $this->relPanel->relEquipo->ip, $this->relPanel->relEquipo->getUsuario(), $this->relPanel->relEquipo->getPassword());
                    //check totales
                    if ($apiMikro)
                    {
                        $apiMikro->checkTotales();
                        //si cambio nombre cargo
                        if (isset($array['bajada']))
                        {
                            $answerType = $apiMikro->getTypeNumbers($this->id);
                            $apiMikro->modificarPlanType(['numbers' => $answerType['down'], 'pcq-rate' => $this->bajada], null, 'set');
                        }
                        if (isset($array['subida']))
                        {
                            $answerType = $apiMikro->getTypeNumbers($this->id);
                            $apiMikro->modificarPlanType(null, ['numbers' => $answerType['up'], 'pcq-rate' => $this->subida], 'set');
                        }
                    }else return false;
                    unset($apiMikro);
            }
        return true;
    }
}
