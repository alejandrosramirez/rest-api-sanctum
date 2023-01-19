<?php

use App\Http\Controllers\ApiAdmin\Admin\AdminController;
use App\Http\Controllers\ApiAdmin\Auth\AuthenticateController;
use App\Http\Controllers\ApiAdmin\Permission\PermissionController;
use App\Http\Controllers\ApiAdmin\Permission\RoleController;
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

Route::middleware('api_admin')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::controller(AuthenticateController::class)->group(function (): void {
            Route::post('/login', 'login');
        });
    });
});

Route::middleware('auth:api_admin')->group(function (): void {
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

    Route::prefix('admins')->controller(AdminController::class)->group(function (): void {
        Route::get('/', 'index')->middleware('permission:admins_read');
        Route::post('/', 'store')->middleware('permission:admins_create');
        Route::get('/{admin}', 'show')->middleware('permission:admins_read');
        Route::put('/{admin}', 'update')->middleware('permission:admins_update');
        Route::delete('/{admin}', 'destroy')->middleware('permission:admins_delete');
        Route::patch('/{admin}/disable', 'disable')->middleware('permission:admins_disable');
    });
});
