<?php

namespace App\Http\Controllers;

use App\Models\Entity_has_file;
use App\Models\Site;
use App\Models\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nodes = Site::select('*')->get();
        return view('adminNodos', ['nodes' => $nodes, 'nodos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    public function createArchivoSitio($id)
    {
        return view('agregarArchivoSitio', ['sitio_id' => $id, 'nodos' => 'active']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNodo($id)
    {
        $site = Site::find($id);
        $archivos = Entity_has_file::select('*')->where('entidad_id', $id)->get();
        //dd($archivos);
        $paneles = Panel::select('*')->where('num_site', $id)->get();
        foreach ($paneles as $panel) {
            $imagenes[] =
            $this->buscarEntityHasFile($panel->id, 'PANEL', 'COVER');
        }
        //dd($imagenes);
        return view('nodo', ['archivos' => $archivos, 'imagenes' => $imagenes, 'site' => $site, 'paneles' => $paneles, 'nodos' => 'active'] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    private function buscarEntitysHasFile ($id, $entidad, $tipo, $tipo2 = null, $paginado = 10)
    {
        if($paginado == "")
        {
            return
            Entity_has_file::select('*')
            ->where('entidad_id', $id)
            ->where('entidad', $entidad)
            ->where(function (Builder $query) use ($tipo, $tipo2)
            {
                return $query->where('tipo', $tipo)
                            ->orWhere('tipo', $tipo2);
            })
            ->get();
        } else 
            {
                return
                Entity_has_file::select('*')
                ->where('entidad_id', $id)
                ->where('entidad', $entidad)
                ->where(function (Builder $query) use ($tipo, $tipo2)
                {
                    return $query->where('tipo', $tipo)
                                ->orWhere('tipo', $tipo2);
                })
                ->paginate($paginado);
            }
    }
    
    private function buscarEntityHasFile ($id, $entidad, $tipo)
    {
        return
        Entity_has_file::select('*')
            ->where('entidad_id', $id)
            ->where('entidad', $entidad)
            ->where('tipo', $tipo)
            ->first();
    }
    public function editArchivosSitio($id)
    {
        $files = $this->buscarEntitysHasFile($id, 'SITIO', 'FILE', 'PHOTO');
        return view('adminArchivosSitio', [ 'files' => $files, 'sitio_id' => $id, 'nodos' => 'active']);
    }
    public function editFileSitio($id)
    {
        $file = $this->buscarEntityHasFile($id, 'SITIO', 'SCHEME');
        return view('modificarFileSitio', [ 'file' => $file, 'sitio_id' => $id, 'nodos' => 'active']);
    }
    
    public function editFilePanel($panel_id, $sitio_id)
    {
        $file = $this->buscarEntityHasFile($panel_id, 'PANEL', 'COVER');
        return view('modificarFilePanel', [ 'file' => $file, 'panel_id' =>$panel_id, 'sitio_id' => $sitio_id, 'nodos' => 'active']);
    }

    public function updateFileSitio(Request $request)
    {
        //if ($cobertura !== $Panel->cobertura && $cobertura != 'sinMapa.svg') {$Panel->cobertura = $cobertura;}
        // $cobertura = $this->subirImagen($request);
        // para validate 
        //si no enviaron archivo
        $schemeImagen = 'sinMapa.svg';
        //si enviaron archivo
        if ($request->file('scheme_file')) {
            //renombrar archivo
            # time().extension
            $request->validate(['scheme_file' => 'nullable|mimes:jpg,jpeg,png,gif,svg,webp|max:4096']);
            $schemeImagen = time() . '.' . $request->file('scheme_file')->clientExtension();

            //subir imagen a directorio productos
            $request->file('scheme_file')->move(public_path('/imgUsuarios'), $schemeImagen);
        }
        if (null != $request->archivoId)
        {
            $entity_has_file = Entity_has_file::find($request->archivoId);
            if ($entity_has_file->file_name != 'sinMapa.svg')
            {
                File::delete(public_path('imgUsuarios/'.$entity_has_file->file_name));
            }
        } else 
            {
            $entity_has_file = new Entity_has_file;
            $entity_has_file->entidad_id = $request->input('sitioId');
            $entity_has_file->entidad = 'SITIO';
            $entity_has_file->tipo = 'SCHEME';
            }
        $entity_has_file->file_name = $schemeImagen;
        $entity_has_file->save();
        return redirect('mostrarNodo/' . $request->input('sitioId'));
    }
    
    public function updateArchivoSitio(Request $request)
    {
        $request->validate(['scheme_file' => 'required|mimes:pdf,jpg,jpeg,png|max:4096']);
        $schemeImagen = $request->file('scheme_file')->getClientOriginalName();
        if ($request->file('scheme_file')->clientExtension() == 'pdf')
        {
            $request->file('scheme_file')->move(public_path('/imgUsuarios/pdf'), $schemeImagen);
            $tipo = 'FILE';
        } else 
            {
            $request->file('scheme_file')->move(public_path('/imgUsuarios/photos'), $schemeImagen);
            $tipo = 'PHOTO';
            }
        $entity_has_file = new Entity_has_file;
        $entity_has_file->entidad_id = $request->input('sitioId');
        $entity_has_file->entidad = 'SITIO';
        $entity_has_file->tipo = $tipo;
        $entity_has_file->file_name = $schemeImagen;
        $entity_has_file->save();
        return redirect('adminArchivosSitio/' . $request->input('sitioId'));
    }

    public function updateFilePanel(Request $request)
    {
        $coverImagen = 'sinMapa.svg';
        if ($request->file('cover_file')) {
            $request->validate(['cover_file' => 'nullable|mimes:jpg,jpeg,png,gif,svg,webp|max:4096']);
            $coverImagen = time() . '.' . $request->file('cover_file')->clientExtension();
            $request->file('cover_file')
            ->move(public_path('/imgUsuarios'), $coverImagen);
        }
        if (null != $request->archivoId)
        {
            $entity_has_file = Entity_has_file::find($request->archivoId);
            if ($entity_has_file->file_name != 'sinMapa.svg') 
            {
                File::delete(public_path('imgUsuarios/' . $entity_has_file->file_name));
            }
        } else 
            {
            $entity_has_file = new Entity_has_file;
            $entity_has_file->entidad_id = $request->input('panelId');
            $entity_has_file->entidad = 'PANEL';
            $entity_has_file->tipo = 'COVER';
            }
        $entity_has_file->file_name = $coverImagen;
        $entity_has_file->save();
        return redirect('mostrarNodo/' . $request->input('sitioId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyArchivo($archivo_id, $sitio_id)
    {
        // si es pdf la carpeta es imgUsuarios/pdf
        $aBorrar = Entity_has_file::find($archivo_id);
        if ($aBorrar->tipo == 'FILE')
        {
            //borrar pdf
            $carpeta = 'imgUsuarios/pdf/' . $aBorrar->file_name;
        } else {
            //borrar photos
            $carpeta = 'imgUsuarios/photos/' . $aBorrar->file_name;
        }
        //dd($carpeta);
        File::delete(public_path($carpeta));
        $aBorrar->delete();
        //devover a vista adminArvhivosSitio/sitio_id con mensaje de OK.
        $respuesta[] = 'Archivo se eliminó correctamente';
        $files = $this->buscarEntitysHasFile($sitio_id, 'SITIO', 'FILE', 'PHOTO');
        return redirect('adminArchivosSitio/'. $sitio_id)->with('mensaje', $respuesta);;
    }
}