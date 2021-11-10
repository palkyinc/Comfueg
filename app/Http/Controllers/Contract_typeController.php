<?php

namespace App\Http\Controllers;

use App\Models\Contract_type;

use Illuminate\Http\Request;

class Contract_typeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = Contract_type::paginate(10);
        return view('adminContractTypes', ['tipos' => $tipos, 'sistema' => 'active']);
    }
    
    /**
     * Send a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRest()
    {
        $tipos = Contract_type::select('id', 'nombre')->get();
        return response()->json($tipos, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarContractType', ['sistema' => 'active']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|min:3|max:45']);
        $contract_type = new Contract_type;
        $contract_type->nombre = $request->input('nombre');
        $contract_type->save();
        $respuesta[] = 'Tipo de Contrato se creo correctamente';
        return redirect('/adminContractTypes')->with('mensaje', $respuesta);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract_type = Contract_type::find($id);
        return view('modificarContractType', ['elemento' => $contract_type, 'sistema' => 'active']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate(['nombre' => 'required|min:3|max:45']);
        $contract_type = Contract_type::find($request->input('id'));
        $contract_type->nombre = $request->input('nombre');
        $respuesta[] = 'Se cambió con exito:';
        if ($contract_type->nombre != $contract_type->getOriginal()['nombre']) {
            $respuesta[] = ' Nombre: ' . $contract_type->getOriginal()['nombre'] . ' POR ' . $contract_type->nombre;
        }
        $contract_type->save();
        return redirect('adminContractTypes')->with('mensaje', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
