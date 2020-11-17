<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        return view('adminUsers', ['Users' => $Users, 'datos' => 'active']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $User = User::find($id);
        return view('modificarUser', ['elemento' => $User, 'datos' => 'active']);
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
        } else {
            $condicion = 'required|email:rfc,dns|unique:users,email';
        }
        $request->validate(
            [
                'name' => 'required|min:2|max:255',
                'email' => $condicion
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
