<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use App\Models\Panel;
use App\Models\Panel_has_barrio;
use Illuminate\Http\Request;

class panel_has_barrioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paneles = Panel::select('*')->where('rol', 'PANEL')->paginate(10);
        return view('adminPanelhasBarrio', ['paneles' => $paneles, 'nodos' => 'active']);
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
        $barrios = Barrio::all();
        $panelHasBarrios = Panel_has_barrio::select('*')->where('panel_id', $id)->get();
        return view ('agregarBarrioToPanel', ['panelHasBarrios' => $panelHasBarrios, 'panel_id' => $id, 'barrios' => $barrios, 'nodos' => 'active']);
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
        $barrios = Barrio::all();
        foreach ($barrios as $barrio) 
        {
            $panelHasBarrio = Panel_has_barrio::where('panel_id', $request->input('id'))
                                            ->where('barrio_id', $barrio->id)
                                            ->first();
            if (isset($panelHasBarrio->id) && null == $request->input($barrio->id))
            {
                $panelHasBarrio->delete();
                $respuesta[] = 'Se eliminó barrio ' . $barrio->nombre . ' de Panel con ID ' . $request->input('id') . ' correctamente.';
            } elseif (!isset($panelHasBarrio->id) && null != $request->input($barrio->id))
                    {
                    $panelHasBarrio = new Panel_has_barrio;
                    $panelHasBarrio->panel_id = $request->input('id');
                    $panelHasBarrio->barrio_id = $barrio->id;
                    $panelHasBarrio->save();
                    $respuesta[] = 'Se agregó barrio ' . $barrio->nombre . 'a Panel con ID ' . $request->input('id') . ' correctamente.';
                    }
        }
        return redirect('adminPanelhasBarrio')->with('mensaje', $respuesta);
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
