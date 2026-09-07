<?php

use App\Http\Controllers\Api\V1\BankController;
use App\Http\Controllers\Api\V1\BusinessCalendarController;
use App\Http\Controllers\Api\V1\ExchangeRateController;
use App\Http\Controllers\Api\V1\HolidayController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\MetaController;
use App\Http\Controllers\Api\V1\PhoneController;
use App\Http\Controllers\Api\V1\TelecomController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:api')->group(function (): void {
    Route::get('/meta', MetaController::class);

    Route::prefix('locations')->group(function (): void {
        Route::get('/municipalities', [LocationController::class, 'municipalities']);
        Route::get('/municipalities/{slug}', [LocationController::class, 'municipality']);
        Route::get('/cities', [LocationController::class, 'cities']);
    });

    Route::prefix('phone')->group(function (): void {
        Route::post('/normalize', [PhoneController::class, 'normalize']);
        Route::post('/validate', [PhoneController::class, 'validateNumber']);
    });

    Route::get('/telecom/operators', [TelecomController::class, 'operators']);

    Route::get('/banks', [BankController::class, 'index']);
    Route::get('/banks/{slug}', [BankController::class, 'show']);

    Route::get('/holidays', [HolidayController::class, 'index']);
    Route::get('/holidays/{year}', [HolidayController::class, 'year'])->whereNumber('year');

    Route::get('/exchange-rates', [ExchangeRateController::class, 'index']);
    Route::get('/exchange-rates/{currency}', [ExchangeRateController::class, 'show']);
    Route::post('/currency/convert', [ExchangeRateController::class, 'convert']);

    Route::prefix('calendar')->group(function (): void {
        Route::get('/is-business-day', [BusinessCalendarController::class, 'isBusinessDay']);
        Route::get('/next-business-day', [BusinessCalendarController::class, 'nextBusinessDay']);
        Route::get('/business-days', [BusinessCalendarController::class, 'businessDays']);
    });
});
