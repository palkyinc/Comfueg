<?php

namespace App\Http\Controllers;

use App\Models\Mail_group;
use App\Models\Site_has_incidente;
use App\Models\User;
use Illuminate\Http\Request;

class Mail_groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = strtoupper($request->input('nombre'));
        $Mail_groups = Mail_group::select("*")
            ->whereRaw("UPPER(name) LIKE (?)", ["%{$name}%"])
            ->paginate(10);
        return view('adminMailGroups', ['Mail_groups' => $Mail_groups, 'sistema' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agregarMail_group', ['sistema' => 'active']);
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
        $mailGroup = new Mail_group();
        $mailGroup->name = $request->input('name');
        $mailGroup->save();
        $respuesta[] = 'Grupo de mail se creó correctamente';
        return redirect('/adminMailgroups')->with('mensaje', $respuesta);
    }
    
    private function validar ($request)
    {
        $request->validate( 
                    [
                     'name' => 'required|min:2|max:55'   
                    ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::all();
        $mail_group = Mail_group::find($id);
        $users_agregados = $mail_group->relUsers;
        return view('agregarUsersToMail_group', ['usuarios_agregados' => $users_agregados, 'Users' => $users, 'mail_group' => $mail_group , 'sistema' => 'active']);
        //dd($users_agregados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mail_group = Mail_group::find($id);
        return view('modificarMail_group', ['elemento' => $mail_group, 'sistema' => 'active']);
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
        $mail_group = Mail_group::find($request->input('id'));
        $this->validar($request);
        $mail_group->name = $nombre;
        if ($mail_group->name != $mail_group->getOriginal()['name']) {
            $respuesta[] = 'Se cambió con exito:';
            $respuesta[] = ' Nombre: ' . $mail_group->getOriginal()['name'] . ' POR ' . $mail_group->name;
            $mail_group->save();
        }else {
            $respuesta[] = 'No Se realizó ningún cambio.';
        }
        return redirect('adminMailGroups')->with('mensaje', $respuesta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUsersToMail_group (Request $request)
    {
        $mail_group = Mail_group::find($request->input('id'));
        $users = User::all();
        foreach ($users as $user) {
            $usuarioEnTabla = false;
            for ($i=0; $i < count($user->relMail_group); $i++) { 
                if ($user->relMail_group[$i]->id == $mail_group->id)
                {
                    $usuarioEnTabla = true;
                }
            }
            if (null !== ($request->input($user->id)) && !$usuarioEnTabla)
            {
                $mail_group->relUsers()->attach($user->id);
            } elseif (null === ($request->input($user->id)) && $usuarioEnTabla)
                {
                    $mail_group->relUsers()->detach($user->id);
                }
        }
        $respuesta[] = 'Se agregaron/quitaron Usuarios al Grupo de mail: ' . $mail_group->name;
        return redirect('adminMailGroups')->with('mensaje', $respuesta);
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
