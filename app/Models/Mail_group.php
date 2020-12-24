<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Mail_group extends Model
{
    use HasFactory;

    public function relUsers()
    {
        return $this->belongsToMany(User::class, 'mail_group_has_users');
    }
    public static function arrayCorreos ($id)
    {
        $grupo = Mail_group::find($id);
        foreach($grupo->RelUsers as $user)
        {
            $respuesta[] = $user->email;
        }
        return($respuesta);
    }
}
