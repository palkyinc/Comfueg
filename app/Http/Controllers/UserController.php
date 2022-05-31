<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = strtoupper($request->input('nombre'));
        $Users = User::select("*")
            ->whereRaw("UPPER(name) LIKE (?)", ["%{$name}%"])
            ->paginate(10);
        return view('adminUsers', ['Users' => $Users, 'sistema' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarUser', ['sistema' => 'active']);
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
        $User = new User;
        $User->name = $request->input('name');
        $User->email = $request->input('email');
        $User->password = Hash::make($request->input('name'));
        $User->save();
        $respuesta[] = 'Permiso se creó correctamente';
        return redirect('/adminUsers')->with('mensaje', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $User = User::find($id);
        $RolesAdded = $User->getRoleNames();
        $Roles = Role::select("id", "name")->get();
        foreach ($Roles as $Role) {
            foreach ($RolesAdded as $RoleAdded) {
                if ($RoleAdded == $Role->name) {
                    $Role->checked = 1;
                }
            }
            if (null === $Role->checked) {
                $Role->checked = 0;
            }
        }
        return view(
            'agregarRoleToUser',
            [
                'Roles' => $Roles,
                'User' => $User,
                'sistema' => 'active'
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
    public function updateRoleToUser(Request $request)
    {
        $request->validate(
            [
                'role' => 'required',
                ]
            );
        //dd($request);
        $User = User::find($request->input('id'));
        $Roles = $User->getRoleNames();
        foreach ($Roles as $Role) {
            $User->removeRole($Role);
        } 
        if ($request->input('role') != 'none'){
            $User->assignRole($request->input('role'));
        }
        $respuesta[] = 'Se cambió Rol del Usuario ' . $User->name . ' con exito:';
        return redirect('adminUsers')->with('mensaje', $respuesta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $User = User::find($id);
        return view('modificarUser', ['elemento' => $User, 'sistema' => 'active']);
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
        $email = $request->input('email');
        $User = User::find($request->input('id'));
        $this->validar($request, $User->id);
        $User->name = $nombre;
        $User->email = $email;
        $User->save();
        $respuesta[] = 'Se cambió con exito:';
        if ($User->name != $User->getOriginal()['name']) {
            $respuesta[] = ' Nombre: ' . $User->getOriginal()['name'] . ' POR ' . $User->name;
        }
        return redirect('adminUsers')->with('mensaje', $respuesta);
    }

    public function validar(Request $request, $idUser = "")
    {
        if ($idUser) {
            $condicion = 'required|email:rfc,dns|unique:users,email,' . $idUser;
            $condicion2 = 'nullable|min:8|max:25';
        } else {
            $condicion = 'required|email:rfc,dns|unique:users,email';
            $condicion2 = 'required|min:8|max:25';
        }
        $request->validate(
            [
                'name' => 'required|min:2|max:255',
                'email' => $condicion,
                'password' => $condicion2
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

    ### API Methods

    public function search($id)
    {
        $user = User::select('name', 'email')->find($id);
        return response()->json($user, 200);
    }
}
