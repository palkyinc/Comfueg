<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Issue_title;

class Issue extends Model
{
    use HasFactory;

    public function relTitle()
    {
        return $this->belongsTo(Issue_title::class, 'titulo_id', 'id');
    }
    
    public function relAsignado()
    {
        return $this->belongsTo(User::class, 'asignado_id', 'id');
    }
    
    public function relCreator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    
    public function relCliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    
    public function relContrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id', 'id');
    }

    public function getVencida()
    {
        return 'Proximamente';
    }
}
