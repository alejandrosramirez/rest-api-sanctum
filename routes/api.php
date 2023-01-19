<?php

use App\Http\Controllers\Api\Auth\AuthenticateController;
use App\Http\Controllers\Api\Permission\PermissionController;
use App\Http\Controllers\Api\Permission\RoleController;
use App\Http\Controllers\Api\State\StateController;
use App\Http\Controllers\Api\Stripe\WebhookController;
use App\Http\Controllers\Api\User\UserController;
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

Route::middleware('api')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::controller(AuthenticateController::class)->group(function (): void {
            Route::post('/login', 'login');
        });
    });

    Route::prefix('webhook')->controller(WebhookController::class)->group(function (): void {
        Route::post('/stripe', 'handle');
    });
});

Route::middleware('auth:api')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::controller(AuthenticateController::class)->group(function (): void {
            Route::post('/logout', 'logout');
        });
    });

    Route::prefix('permissions')->controller(PermissionController::class)->group(function (): void {
        Route::get('/', 'index')->middleware('permission:roles_create');
    });

    Route::prefix('roles')->controller(RoleController::class)->group(function (): void {
        Route::get('/', 'index')->middleware('permission:roles_read');
        Route::post('/', 'store')->middleware('permission:roles_create');
        Route::get('/{role}', 'show')->middleware('permission:roles_read');
        Route::put('/{role}', 'update')->middleware('permission:roles_update');
        Route::delete('/{role}', 'destroy')->middleware('permission:roles_delete');
    });

    Route::prefix('states')->controller(StateController::class)->group(function (): void {
        Route::get('/', 'index')->middleware('permission:states_read');
    });

    Route::prefix('users')->controller(UserController::class)->group(function (): void {
        Route::get('/', 'index')->middleware('permission:users_read');
        Route::post('/', 'store')->middleware('permission:users_create');
        Route::get('/{user}', 'show')->middleware('permission:users_read');
        Route::put('/{user}', 'update')->middleware('permission:users_update');
        Route::delete('/{user}', 'destroy')->middleware('permission:users_delete');
        Route::patch('/{user}/disable', 'disable')->middleware('permission:users_disable');
    });
});
