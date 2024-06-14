<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ConfigPanelController extends Controller
{
    public function index(Request $request)
    {
         $files = File::files(public_path('configPanels'));
        foreach ($files as $key => $file) {
            $data = explode ("-", $file->getFilename());
            if (isset($data[1])) {
                $isBkp = explode(".", $data[1]);
                if ($isBkp[0] === 'bkp')
                {
                    $dataToView[] = $file;
                }
            }
        }
        return view ('adminConfigPanels', ['files' => $dataToView, 'nodos' => 'active']);
        dd($dataToView);
    }
    public function download($filename)
    {
        $file = public_path('configPanels/' . $filename);
        
        return Response::download($file);
    }
}
