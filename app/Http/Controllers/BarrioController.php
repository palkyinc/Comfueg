<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use Illuminate\Http\Request;

class BarrioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = strtoupper($request->input('nombre'));
        $barrios = Barrio::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$nombre}%"])
            ->paginate(10);
        return view('adminBarrios', ['barrios' => $barrios, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarBarrio', ['datos' => 'active']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validar($request);
        $Barrio = new Barrio;
        $Barrio->nombre = $request->input('nombre');
        $Barrio->limites = $request->input('limites');
        $Barrio->save();
        $respuesta[] = 'Barrio se creo correctamente';
        return redirect('/adminBarrios')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function show(Barrio $barrio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Barrio = Barrio::find($id);
        return view('modificarBarrio', ['elemento' => $Barrio, 'datos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $nombre = $request->input('nombre');
        $limites = $request->input('limites');
        $barrio = Barrio::find($request->input('id'));
        $this->validar($request, $barrio->id);
        $barrio->nombre = $nombre;
        $barrio->limites = $limites;
        $respuesta[] = 'Se cambió con exito:';
        if ($barrio->nombre != $barrio->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $barrio->getOriginal()['nombre'] . ' POR ' . $barrio->nombre;
        }
        if ($barrio->limites != $barrio->getOriginal()['limites']) {
            $respuesta[] = ' Límites: ' . $barrio->getOriginal()['limites'] . ' POR ' . $barrio->limites;
        }
        $barrio->save();
        return redirect('adminBarrios')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idBarrio = "")
    {
        if ($idBarrio) {
            $condicion = 'required|min:2|max:45|unique:barrios,nombre,' . $idBarrio;
        } else {
            $condicion = 'required|min:2|max:45|unique:barrios,nombre';
        }
        $request->validate(
            [
                'nombre' => $condicion,
                'limites' => 'nullable|min:2|max:500'
            ],
            [
                'nombre.required' => 'El campo Nombre es obligatorio',
                'nombre.unique' => 'El campo Nombre no puede repetirse.',
                'nombre.min' => 'El campo Nombre debe tener al menos 2 caractéres',
                'nombre.max' => 'El campo Nombre debe tener 45 caractéres como máximo'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barrio  $barrio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barrio $barrio)
    {
        //
    }
    public function search () {
        $barrios = Barrio::get();
        return response()->json($barrios);
    }

    public function checkArchivo ()
    {
        $nuevosBarrios = 0;
        $file = fopen('barrios.txt', 'r');
        if ($file)
        {
            while(!feof($file))
            {
                $candidatos[] = explode(';', fgets($file))[0];
            }
            fclose($file);
            $barrios = Barrio::get();
            foreach ($barrios as $barrio) {
                $rta = true;
                foreach ($candidatos as $candidato) {
                    if ($barrio->nombre === explode('|', $candidato)[0]) {
                        $rta = false;
                    }
                }
                echo $rta ? ($barrio->nombre . '<br>') : "";
            }
            dd('Fin.');
            foreach ($candidatos as $key => $candidato) 
            {
                $barrio = explode('|', $candidato);
                $nombre = $barrio[0];
                $limites = $barrio[1];
                if ($barrio = Barrio::whereRaw("LOWER(nombre) LIKE (?)", ["%{$this->prepararNombre($nombre)}%"])->first())
                {
                    ## cargar limites al barrio existente
                    $barrio->limites = $limites;
                    $barrio->save();
                } else {
                    ## crear nuevo barrio
                    $nuevosBarrios++;
                    $barrio = new Barrio;
                    $barrio->nombre = $nombre;
                    $barrio->limites = $limites;
                    $barrio->save();
                }
            }
            $respuesta[] = 'Se procesaron:' . (count($candidatos)) . ' Barrios.';
            $respuesta[] = 'Se agregaron: ' . $nuevosBarrios . ' Barrios nuevos.';
        }
        else
        {
            $respuesta[] = 'Error al abrir el archcivo barrios.txt';
        }
        return redirect('adminBarrios')->with('mensaje', $respuesta);
    }
    public function updateGeneral ()
    {
        $nuevosBarrios = 0;
        $file = fopen('barrios.txt', 'r');
        if ($file)
        {
            while(!feof($file))
            {
                $candidatos[] = explode(';', fgets($file))[0];
            }
            fclose($file);
            foreach ($candidatos as $key => $candidato) 
            {
                $barrio = explode('|', $candidato);
                $nombre = $barrio[0];
                $limites = $barrio[1];
                if ($barrio = Barrio::whereRaw("LOWER(nombre) LIKE (?)", ["%{$this->prepararNombre($nombre)}%"])->first())
                {
                    ## cargar limites al barrio existente
                    $barrio->limites = $limites;
                    $barrio->save();
                } else {
                    ## crear nuevo barrio
                    $nuevosBarrios++;
                    $barrio = new Barrio;
                    $barrio->nombre = $nombre;
                    $barrio->limites = $limites;
                    $barrio->save();
                }
            }
            $respuesta[] = 'Se procesaron:' . (count($candidatos)) . ' Barrios.';
            $respuesta[] = 'Se agregaron: ' . $nuevosBarrios . ' Barrios nuevos.';
        }
        else
        {
            $respuesta[] = 'Error al abrir el archcivo barrios.txt';
        }
        return redirect('adminBarrios')->with('mensaje', $respuesta);
    }

    public function prepararNombre ($candidata)
    {
        $letras = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'º', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'ü', 'ö', 'Ö');
        foreach ($letras as $letra)
        {
            if (stripos($candidata, $letra))
            {
                $explode = explode($letra, $candidata);
                $masLargo = 0;
                for ($i=0; $i < count($explode); $i++) 
                {
                    if (strlen($explode[$i]) > $masLargo)
                    {
                        $candidata = $explode[$i];
                        $masLargo = strlen($explode[$i]);
                    }
                }
            }
        }
        return strtolower($candidata);
    }

}// fin de barrio
