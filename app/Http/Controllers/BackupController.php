<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $archivos = $this->getArchivos();
        return view ('adminBackups', ['archivos' => $archivos, 'sistema' => 'active']);
    }

    private function getArchivos ()
    {
        $archivos = Storage::disk('local')->files('/Comfueg-SLAM');
        foreach ($archivos as $key => $archivo) {
            $archivos[$key] = ['name' => explode("/", $archivo)[1], 'size' => Storage::size($archivo)];
        }
        return $archivos;
    }
    
    public function syncCloud()
    {
        if (Artisan::call('palky:syncGdrive', ['action' => 'DOWN']))
        {
            $respuesta[] = 'Error al sincronizar.';
        }else{
            $respuesta[] = 'Sincronización finalizada.';
        }
        return redirect('/adminBackups')->with('mensaje', $respuesta);
    }

    public function restoreBackup(Request $request)
    {
        $archivos = $this->getArchivos();
        if (Artisan::call('palky:restoreBkpSlam', ['--file' => $archivos[$request['file']]['name']]))
        {
            $respuesta[] = 'Error: Al restaurar Backup.';
        }else{
            $respuesta[] = 'Exito: Restauración Completada.';
        }
        return redirect('/adminBackups')->with('mensaje', $respuesta);
    }

    public function restoreFile ($file)
    {
        return view ('restoreBackup', ['archivo' => $file, 'sistema' => 'active']);
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
     * @param  \App\Models\Backup  $backup
     * @return \Illuminate\Http\Response
     */
    public function show(Backup $backup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Backup  $backup
     * @return \Illuminate\Http\Response
     */
    public function edit(Backup $backup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Backup  $backup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Backup $backup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backup  $backup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Backup $backup)
    {
        //
    }
}
