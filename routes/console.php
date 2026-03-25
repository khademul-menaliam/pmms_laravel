<?php

use App\Support\BackupManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('pmms:backup', function () {
    $path = BackupManager::storeSnapshot();

    $this->info('Backup saved to storage/app/'.$path);
})->purpose('Create a JSON backup snapshot for PMMS');

Schedule::command('pmms:backup')->dailyAt('23:30');
