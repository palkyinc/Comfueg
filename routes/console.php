<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('syncGdrive', function () {
$this->info('This command is by console @Palkyinc');
})->purpose('Sync contra o desde Gdrive by Palky');

Artisan::command('restoreMysql', function () {
$this->info('This command is by console @Palkyinc');
})->purpose('Restore Slam database from backup to MySql by Palky');
