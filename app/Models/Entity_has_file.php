<?php

namespace App\Models;

use App\Models\Modelo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Entity_has_file extends Model
{
    use HasFactory;

    public function relModelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id', 'id');
    }
    public static function grabarPdfImage($archivo, $entidad_id, $modelo_id)
    {
        $schemeImagen = $archivo->getClientOriginalName();
        if ($archivo->clientExtension() == 'pdf') {
            $archivo->move(public_path('/imgUsuarios/pdf'), $schemeImagen);
            $tipo = 'FILE';
        } else {
            $archivo->move(public_path('/imgUsuarios/photos'), $schemeImagen);
            $tipo = 'PHOTO';
        }
        $entity_has_file = new Entity_has_file;
        $entity_has_file->entidad_id = $entidad_id;
        $entity_has_file->modelo_id = $modelo_id;
        $entity_has_file->tipo = $tipo;
        $entity_has_file->file_name = $schemeImagen;
        $entity_has_file->save();
    }

    public static function getArchivosEntidad($modelo_id, $entidad_id)
    {
        return Entity_has_file::where('modelo_id', $modelo_id)->where('entidad_id', $entidad_id)->get();
    }

    public function deleteArchivo ()
    {
        if ($this->tipo == 'FILE') {
            //borrar pdf
            $carpeta = 'imgUsuarios/pdf/' . $this->file_name;
        } else {
            //borrar photos
            $carpeta = 'imgUsuarios/photos/' . $this->file_name;
        }
        //dd($carpeta);
        if (File::delete(public_path($carpeta)))
        {
            return true;
        } else {
            return false;
        }
        
    }
                    
}
