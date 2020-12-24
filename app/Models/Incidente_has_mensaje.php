<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidente_has_mensaje extends Model
{
    use HasFactory;

    public function site_has_incidente ()
    {
        return $this->belongsTo(site_has_incidente::class);
    }
    public function relUser()
    {
        return $this->belongsTo('App\Models\User', 'user_creator', 'id');
    }
}
