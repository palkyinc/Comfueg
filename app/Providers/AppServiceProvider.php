<?php

namespace App\Providers;

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
    public function boot()
    {
        Paginator::useBootstrap();
        FacadesDB ::listen(function($query)
            {
                FacadesLog::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        FacadesDB::listen(function ($query) {
            if (explode(" ", $query->sql)[0] !== 'select')
            {
                File::append(
                    storage_path('/logs/query.log'),
                    '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL .
                    '[' . auth()->user()->id . ' , ' . auth()->user()->name . ']' . PHP_EOL . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL . PHP_EOL
                );
            }
        });
    }
}
