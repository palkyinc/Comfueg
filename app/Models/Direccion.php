<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'direcciones';

## relacion a tabla calles
public function relCalle () 
{
    return $this->belongsTo('App\Models\Calle', 'id_calle', 'id');
}
public function relEntrecalle1()
{
    return $this->belongsTo('App\Models\Calle', 'entrecalle_1', 'id');
}
public function relEntrecalle2()
{
    return $this->belongsTo('App\Models\Calle', 'entrecalle_2', 'id');
}
## relacion a tabla barrios
public function relBarrio () 
{
    return $this->belongsTo('App\Models\Barrio', 'id_barrio', 'id');
}
## relacion a tabla ciudades
public function relCiudad () 
{
    return $this->belongsTo('App\Models\Ciudad', 'id_ciudad', 'id');
}
public function getResumida()
{
    return ($this->numero . ', ' . $this->relCalle->nombre . ', ' . $this->relBarrio->nombre);
}

}//fin de la clase
