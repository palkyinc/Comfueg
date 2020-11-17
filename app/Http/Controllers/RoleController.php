<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = strtoupper($request->input('nombre'));
        $Roles = Role::select("*")
            ->whereRaw("UPPER(name) LIKE (?)", ["%{$name}%"])
            ->paginate(10);
        return view('adminRoles', ['Roles' => $Roles, 'datos' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarRole', ['datos' => 'active']);
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
        Role::create(['name' => $request->input('name')]);
        $respuesta[] = 'Permiso se creó correctamente';
        return redirect('/adminRoles')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Role = Role::find($id);
        $PermissionsAdded = $Role->getPermissionNames();
        $Permissions = Permission::select("id", "name")->get();
        foreach ($Permissions as $Permission) {
            foreach ($PermissionsAdded as $PermissionAdded) {
                if ($PermissionAdded == $Permission->name) {
                    $Permission->checked = 1;
                }
            }
            if (null === $Permission->checked) {
                $Permission->checked = 0;
            }
        }
        return view(
            'agregarPermissionsToRole',
            [
                'Permissions' => $Permissions,
                'Role' => $Role,
                'datos' => 'active'
            ]
        );
    }

    /**
     * Update the Permission added into roles in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePermissionsToRole(Request $request)
    {
        $Role = Role::find($request->input('id'));
        $Permissions = Permission::select("*")->get();
        foreach ($Permissions as $Permission) {
            if (null !== $request->input($Permission->name)) {
                $Permission->assignRole($Role);
            } else {
                $Permission->removeRole($Role);
            }
        }
        $respuesta[] = 'Se agrego/quito permisos del Role '.$Role->name.' con exito:';
        return redirect('adminRoles')->with('mensaje', $respuesta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Role = Role::find($id);
        return view('modificarRole', ['elemento' => $Role, 'datos' => 'active']);
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
        $Role = Role::find($request->input('id'));
        $this->validar($request, $Role->id);
        $Role->name = $nombre;
        $respuesta[] = 'Se cambió con exito:';
        if ($Role->name != $Role->getOriginal()['name']) {
            $respuesta[] = ' Nombre: ' . $Role->getOriginal()['name'] . ' POR ' . $Role->name;
        }
        $Role->save();
        return redirect('adminRoles')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idRole = "")
    {
        if ($idRole) {
            $condicion = 'required|min:2|max:255|unique:roles,name,' . $idRole;
        } else {
            $condicion = 'required|min:2|max:255|unique:roles,name';
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
