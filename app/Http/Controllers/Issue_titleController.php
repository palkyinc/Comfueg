<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue_title;

class Issue_titleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = Issue_title::paginate(10);
        return view ('adminIssuesTitles', ['tipos' => $tipos, 'sistema' => 'active']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('agregarIssueTitle', ['sistema' => 'active']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['titulo' => 'required|min:3|max:50']);
        $issue_title = new Issue_Title;
        $issue_title->title = $request->input('titulo');
        $issue_title->save();
        $respuesta[] = 'Nuevo Titulo de Ticket se creo correctamente';
        return redirect('/adminIssuesTitles')->with('mensaje', $respuesta);
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
        $issue_title = Issue_title::find($id);
        return view('modificarIssueTitle', ['elemento' => $issue_title, 'sistema' => 'active']);
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
        $request->validate(['title' => 'required|min:3|max:45']);
        $issue_title = Issue_title::find($request->input('id'));
        $issue_title->title = $request->input('title');
        $respuesta[] = 'Se cambió con exito:';
        if ($issue_title->title != $issue_title->getOriginal()['title']) {
            $respuesta[] = ' title: ' . $issue_title->getOriginal()['title'] . ' POR ' . $issue_title->title;
        }
        $issue_title->save();
        return redirect('adminIssuesTitles')->with('mensaje', $respuesta);
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
