<?php
return [
    'driver'=>env('SESSION_DRIVER','database'),'lifetime'=>(int)env('SESSION_LIFETIME',120),'expire_on_close'=>false,'encrypt'=>false,'files'=>storage_path('framework/sessions'),'connection'=>env('SESSION_CONNECTION'),'table'=>env('SESSION_TABLE','sessions'),'store'=>env('SESSION_STORE'),'lottery'=>[2,100],'cookie'=>env('SESSION_COOKIE','laravel_digital_menu_session'),'path'=>'/','domain'=>env('SESSION_DOMAIN'),'secure'=>env('SESSION_SECURE_COOKIE'),'http_only'=>true,'same_site'=>'lax','partitioned'=>false,
];
