<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = strtoupper($request->input('nombre'));
        $Permissions = Permission::select("*")
            ->whereRaw("UPPER(name) LIKE (?)", ["%{$name}%"])
            ->paginate(10);
        return view('adminPermissions', ['Permissions' => $Permissions, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarPermission', ['datos' => 'active']);
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
        Permission::create(['name' => $request->input('name')]);
        $respuesta[] = 'Permiso se creó correctamente';
        return redirect('/adminPermissions')->with('mensaje', $respuesta);
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
        $Permission = Permission::find($id);
        return view('modificarPermission', ['elemento' => $Permission, 'datos' => 'active']);
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
        $nombre = $request->input('name');
        $Permission = Permission::find($request->input('id'));
        $this->validar($request, $Permission->id);
        $Permission->name = $nombre;
        $respuesta[] = 'Se cambió con exito:';
        if ($Permission->name != $Permission->getOriginal()['name']) {
            $respuesta[] = ' Nombre: ' . $Permission->getOriginal()['name'] . ' POR ' . $Permission->name;
        }
        $Permission->save();
        return redirect('adminPermissions')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idPermission = "")
    {
        if ($idPermission) {
            $condicion = 'required|min:2|max:255|unique:permissions,name,' . $idPermission;
        } else {
            $condicion = 'required|min:2|max:255|unique:Permissions,name';
        }
        $request->validate(
            [
                'name' => $condicion
            ]
        );
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
