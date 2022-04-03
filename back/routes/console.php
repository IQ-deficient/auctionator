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

Artisan::command('initialize', function () {
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed');
    Artisan::call('serve');
    Artisan::call('schedule:work');
    $this->info("All system nominal!"); // todo: NOT
})->purpose('Initialize all commands that run the application.');
