<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Finexa is ready.');
})->purpose('Display an inspiration message');
