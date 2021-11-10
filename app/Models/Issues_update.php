<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Issue;

class Issues_update extends Model
{
    use HasFactory;

    public function relUsuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
    
    public function relAsignadoAnt()
    {
        return $this->belongsTo(User::class, 'asignadoAnt_id', 'id');
    }
    
    public function relAsignadoSig()
    {
        return $this->belongsTo(User::class, 'asignadoSig_id', 'id');
    }

    public function Issue ()
    {
        return $this->belongsTo(Issue::class);
    }
    
}
