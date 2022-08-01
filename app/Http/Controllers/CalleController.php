<?php

namespace App\Http\Controllers;

use App\Models\Calle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = strtoupper($request->input('nombre'));
        $calles = Calle::select("*")
            ->whereRaw("UPPER(nombre) LIKE (?)", ["%{$nombre}%"])
            ->paginate(10);
        return view('adminCalles', ['calles' => $calles, 'nombre' => $request->input('nombre'), 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarCalle', ['datos' => 'active']);
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
        $Calle = new Calle;
        $Calle->nombre = $request->input('nombre');
        $Calle->save();
        $respuesta[] = 'Calle se creo correctamente';
        return redirect('/adminCalles')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calle  $calle
     * @return \Illuminate\Http\Response
     */
    public function show(Calle $calle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calle  $calle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Calle = Calle::find($id);
        return view('modificarCalle', ['elemento' => $Calle, 'datos' => 'active']);
    }

    public function checkCalle () {
        $calles = Calle::get();
        echo count($calles) . '<br>';
        foreach ($calles as $calle) {
            if ($nombre = $this->prepararNombre($calle->nombre)) {
                if ($calle_dulpicada = (Calle::where('id', '!=', $calle->id)->whereRaw("LOWER(nombre) LIKE (?)", ["%{$nombre}%"])->first())) {
                    echo $calle->nombre . '<br>';
                    echo $calle_dulpicada->nombre . ' | true<hr>';
                }
            } else {
                if ($calle_dulpicada = (Calle::where('id', '!=', $calle->id)->whereRaw("LOWER(nombre)", strtolower($calle->nombre))->first())) {
                    echo $calle_dulpicada->nombre . '<br>';
                }
            }
        }
    }
    public function prepararNombre ($candidata)
    {
        $rta = false;
        $letras = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'º', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'ü', 'ö', 'Ö', 'Ã');
        foreach ($letras as $letra)
        {
            if ($explode = $this->findLetraString($candidata, $letra))
            {
                $masLargo = 0;
                $rta = true;
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
        return $rta ? strtolower($candidata) : false;
    }

    private function findLetraString ($candidata, $letra) {
        $rta = false;
        $len_candidata = strlen($candidata);
        $i = 0;
        while ($i < $len_candidata) {
            if (ord($candidata[$i]) === ord($letra)) {
                $rta[] = (substr($candidata, 0, $i));
                if ($resto = $i + 1  - ($len_candidata - 1)) {
                    $candidata = (substr($candidata, ($i + 1  - ($len_candidata - 1))));
                    $i = -1;
                    $len_candidata = strlen($candidata);
                }
            }
            $i++;
        }
        $rta[] = $candidata;
        return ($rta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calle  $calle
     * @return \Illuminate\Http\Response
     */
    public function updateGeneral ()
    {
        $file = fopen('calles.txt', 'r');
        if ($file)
        {
            while(!feof($file))
            {
                $candidatas[] = explode(';', fgets($file))[0];
            }
            fclose($file);
            foreach ($candidatas as $key => $candidata) 
            {
                if ($nombre = $this->prepararNombre($candidata)) {
                    if (Calle::whereRaw("LOWER(nombre) LIKE (?)", ["%{$nombre}%"])->first())
                    {
                        unset($candidatas[$key]);
                    }
                } else {
                    if (Calle::whereRaw("LOWER(nombre)", strtolower($candidata))->first())
                    {
                        unset($candidatas[$key]);
                    }
                }

            }
            foreach ($candidatas as $candidata)
            {
                $calle = new Calle;
                $calle->nombre = $candidata;
                $calle->save();
            }
            $respuesta[] = 'Se agregaron: ' . (count($candidatas)) . ' Calles nuevas.';
        }
        else
        {
            $respuesta[] = 'Error al abrir el archcivo calles.txt';
        }
        return redirect('adminCalles')->with('mensaje', $respuesta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calle  $calle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calle $calle)
    {
        $nombre = $request->input('nombre');
        $calle = Calle::find($request->input('id'));
        $this->validar($request, $calle->id);
        $calle->nombre = $nombre;
        $respuesta[] = 'Se cambió con exito:';
        if ($calle->nombre != $calle->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $calle->getOriginal()['nombre'] . ' POR ' . $calle->nombre;
        }
        $calle->save();
        return redirect('adminCalles')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idCalle = "")
    {
        if ($idCalle) {
            $condicion = 'required|min:2|max:45|unique:calles,nombre,' . $idCalle;
        } else {
            $condicion = 'required|min:2|max:45|unique:calles,nombre';
        }
        $request->validate(
            [
                'nombre' => $condicion
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
     * @param  \App\Models\Calle  $calle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calle $calle)
    {
        //
    }
    public function search()
    {
        //$calles = Calle::select('nombre')->get();
        $calles = Calle::get();
        return response()->json($calles);
    }
}// fin de la clase
