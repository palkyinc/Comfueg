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
        Commands\syncGdrive::class,
        Commands\restoreBkpSlam::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $emailOutput = 'migvicpereyra@hotmail.com';
        $dia_baja = date('d');
        if ($dia_baja > 10){$dia_baja = 1;}
        // $schedule->command('inspire')->hourly();
        $schedule->call(function(){$this->semanal();})->weeklyOn(1, '09:00')->timezone(Config::get('constants.USO_HORARIO_ARG'))->sendOutputTo('storage/logs/schedule.log');
        $schedule->call(function(){CronFunciones::bkpPaneles();})->weeklyOn(3, '03:00')->timezone(Config::get('constants.USO_HORARIO_ARG'))->sendOutputTo('storage/logs/schedule.log');
        $schedule->call(function(){CronFunciones::diario();})->daily()->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput)->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->command('backup:clean')->daily()->at('04:30')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput)->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->command('backup:run')->daily()->at('20:00')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput)->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->command('palky:syncGdrive UP')->daily()->at('20:30')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput)->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->call(function(){CronFunciones::diario01();})->dailyAt('06:00')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput);
        $schedule->call(function(){CronFunciones::diario02();})->dailyAt('06:10')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput);
        $schedule->call(function(){CronFunciones::diario03();})->dailyAt('06:20')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput);
        $schedule->call(function(){$this->cadaMinuto();})->everyMinute()->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput);
        //$schedule->call(function(){$this->cadaCincoMinutos();})->everyFiveMinutes();
        $schedule->call(function(){$this->mensual();})->monthly()->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput)->timezone(Config::get('constants.USO_HORARIO_ARG'));
        $schedule->call(function(){CronFunciones::bajaAut();})->monthlyOn( $dia_baja, '00:05')->sendOutputTo('storage/logs/schedule.log')->emailOutputOnFailure($emailOutput)->timezone(Config::get('constants.USO_HORARIO_ARG'));
        ### ->monthly(); //Run the task on the first day of every month at 00:00 ->monthlyOn(4, '15:00')
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
    private function cadaCincoMinutos()
    {
        // funcion
    }
    private function mensual ()
    {
        CronFunciones::resetContadores_mensuales();
    }
    private function semanal ()
    {
        CronFunciones::enviarMailDeudasPendientes();
    }
    private function cadaMinuto()
    {
        CronFunciones::readDay();
        CronFunciones::buscarProveedoresCaidos();
    }
}
