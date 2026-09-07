<?php

return [
    'default' => env('CACHE_STORE', 'file'),
    'stores' => [
        'array' => ['driver' => 'array', 'serialize' => false],
        'file' => ['driver' => 'file', 'path' => storage_path('framework/cache/data'), 'lock_path' => storage_path('framework/cache/data')],
    ],
    'prefix' => env('CACHE_PREFIX', 'libya_dev_api_cache_'),
];
