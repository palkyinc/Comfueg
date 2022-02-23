<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use App\Models\Direccion;
use App\Models\Calle;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $calle = strtoupper($request->input('calle'));
        if ($calle)
        {
            $Street = Calle::getCallePorNombre($calle);
            //dd($Street);
            $direcciones = Direccion::with('relCalle', 'relEntrecalle1', 'relBarrio', 'relCiudad', 'relEntrecalle2')
                            ->where("id_calle", $Street->id)
                            ->paginate(10);
        }else   {
                $direcciones = Direccion::with('relCalle', 'relEntrecalle1', 'relBarrio', 'relCiudad', 'relEntrecalle2')->paginate(10);
                }
        return view('adminDirecciones', ['direcciones' => $direcciones, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $calles = Calle::get();
        $barrios = Barrio::get();
        $ciudades = Ciudad::get();
        return view('agregarDireccion', 
                        [
                        'datos' => 'active',
                        'calles' => $calles,
                        'barrios' => $barrios,
                        'ciudades' => $ciudades
                        ]
                    );
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
        $Direccion = new Direccion;
        $Direccion->id_calle = $request->input('id_calle');
        $Direccion->numero = $request->input('numero');
        $Direccion->entrecalle_1 = $request->input('entrecalle_1');
        $Direccion->entrecalle_2 = $request->input('entrecalle_2');
        $Direccion->id_barrio = $request->input('id_barrio');
        $Direccion->id_ciudad = $request->input('id_ciudad');
        if ($existe = Direccion::select('id')->where([['id_calle', $Direccion->id_calle ], ['numero', $Direccion->numero]])->first())
        {
            //dd($existe->id);
            $respuesta[] = 'La dirección ya EXISTE. Es la ID: ' . $existe->id;
        } else  {
                $Direccion->save();
                $respuesta[] = 'Dirección se creo correctamente';
                }
        return redirect('/adminDirecciones')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function show(Direccion $direccion)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Direccion = Direccion::find($id);
        $calles = Calle::get();
        $barrios = Barrio::get();
        $ciudades = Ciudad::get();
        return view('modificarDireccion', [
            'elemento' => $Direccion,
            'barrios' => $barrios,
            'calles' => $calles,
            'ciudades' => $ciudades,
             'datos' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /* $id_calle = $request->input('id_calle');
        $numero = $request->input('numero');
        $entrecalle_1 = $request->input('entrecalle_1');
        $entrecalle_2 = $request->input('entrecalle_2');
        $id_barrio = $request->input('id_barrio');
        $id_ciudad = $request->input('id_ciudad');
        $direccion = Direccion::find($request->input('id'));
        $this->validar($request, $direccion);
        $direccion->id_calle = $id_calle;
        $direccion->numero = $numero;
        $direccion->entrecalle_1 = $entrecalle_1;
        $direccion->entrecalle_2 = $entrecalle_2;
        $direccion->id_barrio = $id_barrio;
        $direccion->id_ciudad = $id_ciudad; */
        $direccion = $this->requestToObject($request, $request->input('id'));
        if ($direccion->id_calle != $direccion->getOriginal()['id_calle']) {
            $respuesta[] = ' Id Calle: ' . $direccion->getOriginal()['id_calle'] . ' POR ' . $direccion->id_calle;
        }
        if ($direccion->numero != $direccion->getOriginal()['numero']) {
            $respuesta[] = ' Número: ' . $direccion->getOriginal()['numero'] . ' POR ' . $direccion->numero;
        }
        if ($direccion->entrecalle_1 != $direccion->getOriginal()['entrecalle_1']) {
            $respuesta[] = ' Entrecalle 1: ' . $direccion->getOriginal()['entrecalle_1'] . ' POR ' . $direccion->entrecalle_1;
        }
        if ($direccion->entrecalle_2 != $direccion->getOriginal()['entrecalle_2']) {
            $respuesta[] = ' Entrecalle 2: ' . $direccion->getOriginal()['entrecalle_2'] . ' POR ' . $direccion->entrecalle_2;
        }
        if ($direccion->id_barrio != $direccion->getOriginal()['id_barrio']) {
            $respuesta[] = ' Id Barrio: ' . $direccion->getOriginal()['id_barrio'] . ' POR ' . $direccion->id_barrio;
        }
        if ($direccion->id_ciudad != $direccion->getOriginal()['id_ciudad']) {
            $respuesta[] = ' Id Ciudad: ' . $direccion->getOriginal()['id_ciudad'] . ' POR ' . $direccion->id_ciudad;
        }
        $direccion->save();
        return redirect('adminDirecciones')->with('mensaje', $respuesta);
    }

    public function validar(Request $request)
    {
        $request->validate(
            [
                'id_calle' => 'required|numeric|min:1|max:99999',
                'numero' => 'required|numeric|min:1|max:99999',
                'id_barrio' => 'required|numeric|min:1|max:99999',
                'id_ciudad' => 'required|numeric|min:1|max:99999',
                'entrecalle_1' => 'nullable|numeric|min:1|max:9999',
                'entrecalle_2' => 'nullable|numeric|min:1|max:9999'
            ]
        );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Direccion $direccion)
    {
        //
    }

    public function search ($street, $numero) {
        if($street && preg_match("/^[0-9]*$/", $numero)) {
            $direccion = Direccion::where('id_calle', $street)->where('numero', $numero)->first();
            if($direccion){
                return response()->json($this->direccionToJson($direccion));
            }
        }
        return response()->json(null);
    }

    private function direccionToJson (Direccion $direccion) {
        return [
                    'id' => $direccion->id,
                    'nombre_calle' => $direccion->relCalle->nombre,
                    'numero' => $direccion->numero,
                    'entrecalle1' => isset ($direccion->relEntrecalle1->nombre) ? $direccion->relEntrecalle1->nombre :'',
                    'entrecalle2' => isset ($direccion->relEntrecalle2->nombre) ? $direccion->relEntrecalle2->nombre :'',
                    'barrio' => $direccion->relBarrio->nombre,
                    'ciudad' => $direccion->relCiudad->nombre,
                    'coordenadas' => isset ($direccion->coordenadas) ? $direccion->coordenadas : '',
                    'comentarios' => isset ($direccion->comentarios) ? $direccion->comentarios : '',
                ];
    }

    public function searchById ($id) {
        if (preg_match("/^[0-9]*$/", $id)) {
            $direccion = Direccion::find($id);
            if ($direccion) {
                return response()->json($this->direccionToJson($direccion));
            }
        }
        return response()->json(null);
    }

    public function storeApi(Request $request){
        $direccion = $this->requestToObject($request);
        $direccion->save();
        return response()->json($direccion->id, 200);
    }
    
    public function updateApi(Request $request){
        $direccion = $this->requestToObject($request, $request->input('id'));
        $direccion->save();
        return response()->json(true, 200);
    }

    private function requestToObject (Request $request, $id_direccion = null) {
        
        $this->validar($request);
        if ($id_direccion) {
            $direccion = Direccion::find($id_direccion);
        }else {
            $direccion = new Direccion;
        }
        $direccion->id_calle = $request->input('id_calle');
        $direccion->numero = $request->input('numero');
        $direccion->entrecalle_1 = $request->input('entrecalle_1');
        $direccion->entrecalle_2 = $request->input('entrecalle_2');
        $direccion->id_barrio = $request->input('id_barrio');
        $direccion->id_ciudad = $request->input('id_ciudad');
        $direccion->coordenadas = ( null !==$request->input('coordenadas')) ? $request->input('coordenadas') : '';
        $direccion->comentarios = ( null !==$request->input('comentarios')) ? $request->input('comentarios') : '';
        return $direccion;
    }
}//fin de la clase
