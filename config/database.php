<?php

use Illuminate\Support\Str;

return [
    'default' => env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => ['driver' => 'sqlite', 'url' => env('DB_URL'), 'database' => env('DB_DATABASE', database_path('database.sqlite')), 'prefix' => '', 'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true), 'busy_timeout' => null, 'journal_mode' => null, 'synchronous' => null],
        'mysql' => ['driver' => 'mysql', 'url' => env('DB_URL'), 'host' => env('DB_HOST', '127.0.0.1'), 'port' => env('DB_PORT', '3306'), 'database' => env('DB_DATABASE', 'laravel'), 'username' => env('DB_USERNAME', 'root'), 'password' => env('DB_PASSWORD', ''), 'unix_socket' => env('DB_SOCKET', ''), 'charset' => env('DB_CHARSET', 'utf8mb4'), 'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'), 'prefix' => '', 'prefix_indexes' => true, 'strict' => true, 'engine' => null],
    ],
    'migrations' => ['table' => 'migrations', 'update_date_on_publish' => true],
    'redis' => ['client' => env('REDIS_CLIENT', 'phpredis'), 'options' => ['cluster' => env('REDIS_CLUSTER', 'redis'), 'prefix' => env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-database-')], 'default' => ['url' => env('REDIS_URL'), 'host' => env('REDIS_HOST', '127.0.0.1'), 'username' => env('REDIS_USERNAME'), 'password' => env('REDIS_PASSWORD'), 'port' => env('REDIS_PORT', '6379'), 'database' => env('REDIS_DB', '0')]],
];
