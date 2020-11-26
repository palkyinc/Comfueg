<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Site;
use App\Models\Panel;

class Nodo extends Model
{
    use HasFactory;
    public static function obtenerNodo ($id)
    {
        $site = Site::find($id);
        $paneles = Panel::select('*')->where('num_site', $id)->get();
        $node[] = $site;
        $node[] = $paneles;
        return $node;

    }
}
