<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\ShellCommands;

class syncGdrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'palky:syncGdrive {action : UP sever contra cloud | DOWN cloud contra server | DELETESERVER borra todo en el cloud | DELETECLOUD borra todo en el cloud} 
                                             {--p|progress : Muestra el progreso en consola}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync contra o desde Gdrive @PalkyInc.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        switch ($this->argument('action')) {
            case 'UP':
                $comando = 'rclone sync --config="/app/docker/rclone/rclone.conf" /datarclone gdrive:Comfueg-SLAM';
                break;
            
            case 'DOWN':
                $comando = 'rclone sync --config="/app/docker/rclone/rclone.conf" gdrive:Comfueg-SLAM /datarclone';
                break;
            
            case 'DELETESERVER':
                $comando = 'rclone delete --config="/app/docker/rclone/rclone.conf" --rmdirs /datarclone';
                break;
            
            case 'DELETECLOUD':
                $comando = 'rclone delete --config="/app/docker/rclone/rclone.conf" --rmdirs gdrive:Comfueg-SLAM';
                break;
            
            default:
                $this->info($this->argument('action') . ' action no válido');
                return Command::FAILURE;
                break;
        }
        if ($this->option('progress'))
        {
            $comando = $comando . ' --progress';
        }
        $this->info('Ejecutando...' . $this->argument('action'));
        $output = new ShellCommands($comando);
        while ($output->status()){}
        $this->info('Finalizado');
        return Command::SUCCESS;
    }
}
