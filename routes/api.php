<?php

use Ferdiunal\NovaPasswordConfirmModal\Http\Controllers\ShowValueController;
use Ferdiunal\NovaPasswordConfirmModal\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

Route::post('/verify/{resource}/{resourceId}', VerifyController::class)->middleware('throttle:60,1');
Route::get('/show/{resource}/{resourceId}/{attribute}/{uniqueId}', ShowValueController::class)
    ->middleware('throttle:10,10')
    ->name('nova-password-confirm-modal.show');
