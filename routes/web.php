<?php

use App\Http\Controllers\Artisan\ArtisanController;
use App\Http\Controllers\Web\Application\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('')->controller(ApplicationController::class)->group(function () {
    Route::get('/', 'index')->where('any', '.*');
});

Route::prefix('artisan')->controller(ArtisanController::class)->group(function () {
    Route::get('/cache', 'cache');
    Route::get('/config', 'config');
    Route::get('/route', 'route');
});
