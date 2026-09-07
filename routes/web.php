<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'service' => 'libya-dev-api',
    'api_version' => config('libya.api_version'),
    'time' => now('UTC')->toIso8601String(),
]));
Route::view('/docs', 'docs');
Route::get('/openapi.json', fn () => response()->file(resource_path('openapi/openapi.json'), [
    'Content-Type' => 'application/json; charset=UTF-8',
]));
