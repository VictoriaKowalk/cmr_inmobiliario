<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sistema:backup')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->when(fn () => (bool) config('backup.habilitado'));

Schedule::command('queue:prune-failed --hours=336')
    ->dailyAt('03:00')
    ->withoutOverlapping();

Schedule::command('visitas:enviar-recordatorios')
    ->everyFiveMinutes()
    ->withoutOverlapping();
