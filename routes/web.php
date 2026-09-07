<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/docs', 'docs');
Route::get('/openapi.json', fn () => response()->file(resource_path('openapi/openapi.json'), [
    'Content-Type' => 'application/json; charset=UTF-8',
]));
