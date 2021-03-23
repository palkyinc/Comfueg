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
                ### Verificar que no hay clientes con este plan
                $panelAnt = Panel::find($this->getOriginal()['gateway_id']);
                $apiMikro = GatewayMikrotik::getConnection($panelAnt->relEquipo->ip, $panelAnt->relEquipo->getUsuario(), $panelAnt->relEquipo->getPassword());
                if ($apiMikro)
                {
                    $apiMikro->checkTotales();
                    $answerType = $apiMikro->getTypeNumbers($this->id);
                    $answerTree = $apiMikro->getTreeNumbers($this->id);
                    $answerMangle = $apiMikro->getMangleNumbers($this->id);
                    if ($answerType)
                    {
                        $apiMikro->modificarPlanType(['numbers' => $answerType['down']], ['numbers' => $answerType['up']], 'remove');
                    }
                    if ($answerTree)
                    {
                        $apiMikro->modificarPlanTree(['numbers' => $answerTree['down']], ['numbers' => $answerTree['up']], 'remove');
                    }
                    if ($answerMangle) 
                    {
                        $apiMikro->modificarPlanMangle(['numbers' => $answerMangle['down']], ['numbers' => $answerMangle['up']], 'remove');
                    }
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
                            $apiMikro->modificarPlanType(['numbers' => $answerType['down'], 'pcq-rate' => $this->bajada . 'K'], null, 'set');
                        }
                        if (isset($array['subida']))
                        {
                            $answerType = $apiMikro->getTypeNumbers($this->id);
                            $apiMikro->modificarPlanType(null, ['numbers' => $answerType['up'], 'pcq-rate' => $this->subida . 'K'], 'set');
                        }
                    }else return false;
                    unset($apiMikro);
            }
        return true;
    }

    public function reconcileMikrotik()
    {
        if ($this->gateway_id)
        {
            $apiMikro = GatewayMikrotik::getConnection($this->relPanel->relEquipo->ip, $this->relPanel->relEquipo->getUsuario(), $this->relPanel->relEquipo->getPassword());
            if ($apiMikro) 
            {
                $planesEnMikrotik = $apiMikro->getPlanesData();
                unset($apiMikro);
                foreach ($planesEnMikrotik['type'] as $value) {
                    if ($value['name'] == $this->id . '_up' && 
                        $value['pcq-rate'] == ($this->subida *1000) &&
                        $value['kind'] == 'pcq' &&
                        $value['pcq-classifier'] == 'src-address')
                    {
                        $type['subida'] = 1;
                    }
                    if ($value['name'] == $this->id . '_down' && 
                        $value['pcq-rate'] == ($this->bajada *1000) &&
                        $value['kind'] == 'pcq' &&
                        $value['pcq-classifier'] == 'dst-address')
                    {
                        $type['bajada'] = 1;
                    }
                }
                if(!isset($type['subida']) || !isset($type['bajada'])) {return false;}
                foreach ($planesEnMikrotik['tree'] as $value) {
                    if ($value['name'] == 'UPLOAD_' . $this->id &&
                        $value['parent'] == 'UPLOAD_TOTAL' &&
                        $value['queue'] == $this->id . '_up')
                    {
                            $tree['subida'] = 1;
                    }
                    if ($value['name'] == 'DOWNLOAD_' . $this->id &&
                        $value['parent'] == 'DOWNLOAD_TOTAL' &&
                        $value['queue'] == $this->id . '_down')
                    {
                            $tree['bajada'] = 1;
                    }
                }
                if(!isset($tree['subida']) || !isset($tree['bajada'])) {return false;}
                foreach ($planesEnMikrotik['mangle'] as $value) {
                    if ($value['chain'] =='forward' &&
                        isset($value['src-address-list']) &&
                        $value['src-address-list'] == $this->id &&
                        $value['action'] == 'mark-packet' &&
                        $value['new-packet-mark'] == "UPLOAD_" . strtoupper($this->id) &&
                        $value['passthrough'] == 'false' )
                    {
                            $mangle['subida'] = 1;
                    }
                    if ($value['chain'] == 'forward' &&
                        isset($value['dst-address-list'])  &&
                        $value['dst-address-list'] == $this->id &&
                        $value['action'] == 'mark-packet' &&
                        $value['new-packet-mark'] == "DOWNLOAD_" . strtoupper($this->id) &&
                        $value['passthrough'] == 'false' )
                    {
                            $mangle['bajada'] = 1;
                    }
                }
                if(!isset($mangle['subida']) || !isset($mangle['bajada'])) {return false;}
                    else { return true;}
                dd($planesEnMikrotik);
            }
                            

        }
        else 
        {
            return true;
            # chequear que no existe este id de plan en ningun gateway.
        }
    }
}
