<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\ShellCommands;
use ZanySoft\Zip\Zip;

class restoreBkpSlam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'palky:restoreBkpSlam {--f|file= : Nombre del archivo .zip a restaurar en storage/app/Comfueg-SLAM/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restaura base de datos de Backup para SLAM @Palkyinc.';

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
        //$this->info(env('DB_HOST'));
        $carpeta = 'storage/app/';
        if ( $file = $this->option('file'))
        {
            if (file_exists( $carpeta . '/Comfueg-SLAM/' . $file)) {
                ##Comprobar Si existe la BD Slam
                exec('mysql --host=' . env('DB_HOST') . ' --port=' . env('DB_PORT') . ' --user=' . env('DB_USERNAME') . ' --password=' . env('DB_PASSWORD') . " -e 'use slam'", $output, $status);
                if ($status) {
                    $this->info('ERROR: al conectar a la Base de datos Slam');
                    return Command::FAILURE;
                }
                $zip = Zip::open($carpeta . '/Comfueg-SLAM/' . $file);
                exec('rm -Rf storage/app/backup-temp', $output, $status);
                if ($status) {
                    $this->info('ERROR: al borrar carpeta backup-temp');
                    return Command::FAILURE;
                }
                $zip->extract('storage/app/backup-temp');
                ## Restaurar Base de datos
                $comando = 'mysql --host=' . env('DB_HOST') . ' --port=' . env('DB_PORT') . ' --user=' . env('DB_USERNAME') . ' --password=' . env('DB_PASSWORD') . ' --database=slam < storage/app/backup-temp/db-dumps/mysql-slam.sql';
                $this->info('Restaurando Base de datos....');
                exec($comando, $output, $status);
                if ($status) {
                    $this->info('ERROR: al restaurar la Base de datos Slam');
                    return Command::FAILURE;
                }
                $this->info('EXITO: al restaurar la Base de datos Slam');
                ## Copiar archivo a ubicaciones originales
                exec('cp -RT ' . $carpeta . '/backup-temp/app/public/ public/', $output, $status);
                if ($status) {
                    $this->info('ERROR: al copiar carpeta Public');
                    return Command::FAILURE;
                }
                exec('cp -RT ' . $carpeta . '/backup-temp/app/storage/ storage/', $output, $status);
                if ($status) {
                    $this->info('ERROR: al copiar carpeta Storage');
                    return Command::FAILURE;
                }
                $this->info('EXITO: al restaurar Archivos!');
                return Command::SUCCESS;
            }
            else {
                $this->info('ERROR: No se encontró archivo: ' . $file);
                return Command::FAILURE;
            }
        }
        else {
            $this->info('ERROR: Falta especificar --f|file= Nombre del archivo .zip a restaurar en storage/app/Comfueg-SLAM/');
            return Command::FAILURE;
        }
    }
}
