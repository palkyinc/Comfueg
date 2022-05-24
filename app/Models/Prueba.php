<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Prueba extends Model
{
    use HasFactory;

    public function relUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
