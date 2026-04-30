<?php

use App\Http\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', [SwaggerController::class, 'index'])->name('swagger.ui');
Route::get('/docs/openapi.json', [SwaggerController::class, 'spec'])->name('swagger.spec');
