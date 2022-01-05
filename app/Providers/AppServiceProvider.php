<?php

namespace App\Providers;

use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log as FacadesLog;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    {
        Paginator::useBootstrap();

        FacadesDB::listen(function($query)
            {
                FacadesLog::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        FacadesDB::listen(function ($query)
        {
            $explodeSql = explode(" ", $query->sql);
            if ($explodeSql[0] === 'update' || $explodeSql[0] === 'insert')
            {
                File::append(
                    storage_path('/logs/query.log'),
                    '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL .
                    '[' . (auth()->user()->id ?? 'null') . ' , ' . (auth()->user()->name ?? 'Sin Usuario Logueado') . ']' . PHP_EOL . $query->sql . ' [' . $this->implotar(', ', $query->bindings) . ']' . PHP_EOL . PHP_EOL
                );
            }
        });
        
        $charts->register([\App\Charts\SampleChart::class]);
        $charts->register([\App\Charts\SemanalChart::class]);
        $charts->register([\App\Charts\MensualChart::class]);
    }

    private function implotar ($separador, $query)
    {
        foreach ($query as $value) {
            switch (gettype($value)) 
            {
                case 'object':
                    $rta = 'Object';
                    //dd($value->date);
                    break;
                
                case 'string':
                    $rta = $value;
                    break;
                
                case 'integer':
                    $rta = $value;
                    break;
                
                case 'NULL':
                    $rta = 'null';
                    break;
                
                default:
                    $rta = 'Salió por Default';
                    break;
            }

            if (isset($respuesta))
            {
                $respuesta .= $separador . $rta ;
            } else 
                {
                $respuesta = $rta;
                }
            
        }
        return $respuesta;
    }
}
