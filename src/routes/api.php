<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::resource('players', App\Http\Controllers\API\PlayerController::class);

Route::get(
    'players/stats',
    [App\Http\Controllers\API\PlayerController::class, 'index']
)->name('palyers.index')->middleware('cache.stats');

Route::post(
    'players/stats',
    [App\Http\Controllers\API\PlayerController::class, 'store']
)->name('palyers.store');