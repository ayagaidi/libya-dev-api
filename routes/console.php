<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('libya:about', function () {
    $this->info('Libya Dev API — open developer infrastructure for Libya.');
});
