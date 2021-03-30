<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Custom\ClientMikrotik;
use App\Custom\GatewayMikrotik;
use Illuminate\Support\Facades\File;
use App\Models\Panel;
use App\Models\Plan;
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
        $schedule->call(function(){$this->readBytes();})->everyMinute();
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

    private function readBytes()
    {
        date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
        $archivo = date('Ymd') . '.dat';
        $hora = date('H.i');
        $gateways = $this->getGateways();
        foreach ($gateways as $elemento)
        {
            $gateway = Panel::find($elemento);
            $apiMikro = GatewayMikrotik::getConnection($gateway->relEquipo->ip, $gateway->relEquipo->getUsuario(), $gateway->relEquipo->getPassword());
            if ($apiMikro) 
            {
                $allData = $apiMikro->getGatewayData();
                foreach ($allData['hotspotHost'] as $elemento)
                {
                    if (isset($elemento['comment']) && is_numeric($elemento['comment'])) 
                    {
                        //dd($elemento['comment'] . ';' . $hora . ';' . $elemento['bytes-in'] . ';' . $elemento['bytes-out']);
                        File::append(
                                storage_path('Crons/' . $archivo),
                                $elemento['comment'] . ';' . $hora . ';' . $elemento['bytes-in'] . ';' . $elemento['bytes-out'] . PHP_EOL
                            );
                    }
                }
                unset($apiMikro);
            }
        }
    }
    private function getGateways ()
    {
        $planes = Plan::select('gateway_id')->where('gateway_id', '!=', null)->get();
        $gateways = null;
        foreach ($planes as $plan)
        {
            if ($gateways === null)
            {
                $gateways[] = $plan->gateway_id;
            }
            else
                {
                    $existe = false;
                    foreach ($gateways as $gateway)
                    {
                        if ($gateway == $plan->gateway_id)
                        {
                            $existe = true;
                        }
                    }
                    if (!$existe)
                    {
                        $gateways[] = $plan->gateway_id;
                    }

                }
        }
        return $gateways;
    }
}
