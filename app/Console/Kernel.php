<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Custom\CronFunciones;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function(){$this->resumenDeudas();})->weeklyOn(1, '09:00')->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->call(function(){$this->genSem();})->daily()->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->call(function(){$this->readDay();})->everyMinute();
        ### ->monthly(); //Run the task on the first day of every month at 00:00
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    private function resumenDeudas ()
    {
        CronFunciones::enviarMailDeudasPendientes();
    }
    
    private function genSem ()
    {
        CronFunciones::generarArchivoSem();
    }

    private function readDay()
    {
        CronFunciones::readDay();
        CronFunciones::buscarProveedoresCaidos();
    }
    
}
