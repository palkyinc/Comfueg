<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use App\Models\Direccion;
use App\Models\Calle;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $direccion = Direccion::find($id);
        $calles = Calle::get();
        $barrios = Barrio::get();
        $ciudades = Ciudad::get();
        return view('modificarDireccion', [
            'elemento' => $direccion,
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
        $direccion = $this->requestToObject($request, $request->input('id'));
        if ($direccion->isDirty())
        {
            $respuesta['success'][] = ' La dirección ID: ' . $direccion->id_calle . ' ha sido modificada.';
            if ($direccion->isDirty('id_calle')) {
                $respuesta['success'][] = ' Id Calle: ' . $direccion->getOriginal()['id_calle'] . ' POR ' . $direccion->id_calle;
            }
            if ($direccion->isDirty('numero')) {
                $respuesta['success'][] = ' Número: ' . $direccion->getOriginal()['numero'] . ' POR ' . $direccion->numero;
            }
            if ($direccion->isDirty('entrecalle_1')) {
                $respuesta['success'][] = ' Entrecalle 1: ' . $direccion->getOriginal()['entrecalle_1'] . ' POR ' . $direccion->entrecalle_1;
            }
            if ($direccion->isDirty('entrecalle_2')) {
                $respuesta['success'][] = ' Entrecalle 2: ' . $direccion->getOriginal()['entrecalle_2'] . ' POR ' . $direccion->entrecalle_2;
            }
            if ($direccion->isDirty('id_barrio')) {
                $respuesta['success'][] = ' Id Barrio: ' . $direccion->getOriginal()['id_barrio'] . ' POR ' . $direccion->id_barrio;
            }
            if ($direccion->isDirty('id_ciudad')) {
                $respuesta['success'][] = ' Id Ciudad: ' . $direccion->getOriginal()['id_ciudad'] . ' POR ' . $direccion->id_ciudad;
            }
            if ($direccion->isDirty('coordenadas')) {
                $respuesta['success'][] = ' Coordenadas: ' . $direccion->getOriginal()['coordenadas'] . ' POR ' . $direccion->coordenadas;
            }
            if ($direccion->isDirty('comentarios')) {
                $respuesta['success'][] = ' Comentarios: ' . $direccion->getOriginal()['comentarios'] . ' POR ' . $direccion->comentarios;
            }
            $direccion->save();
            $direccion->refresh();
            dd($direccion);
            if($contratos = \App\Models\Contrato::where('id_direccion', $direccion->id)->get())
            {
                $descripcion = null;
                foreach ($respuesta as $key => $value) {
                    $descripcion .= implode(". ", $value);
                }
                foreach ($contratos as $key => $contrato) {
                    $ticket = \App\Models\Issue::create([
                        'titulo_id' => 8,
                        'descripcion' => $descripcion,
                        'asignado_id' => Auth::id(),
                        'creator_id' => 1,
                        'cliente_id' => $contrato->relCliente->id,
                        'contrato_id' => $contrato->id,
                        'closed' => true]);
                    $respuesta['success'][] = 'Ticket N°: ' . $ticket->id . ' por cambios en Dirección del contrato N°: ' . $contrato->id;
                }
                return redirect('inicio')->with('mensaje', $respuesta);
            }
        } else {
            $respuesta['info'][] = 'Nada para modificar.';
            return redirect('adminDirecciones')->with('mensaje', $respuesta);
        }
        
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
                'entrecalle_2' => 'nullable|numeric|min:1|max:9999',
                'coordenadas' => 'nullable|min:22|max:40'
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
