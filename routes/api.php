<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SwitchRoleController;
use App\Http\Controllers\Teamwork\AuthController as TeamworkAuthController;
use App\Http\Controllers\Teamwork\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'guest-course'], function () {
            Route::get('accept-invite/{token}', [TeamworkAuthController::class, 'validateToken']);
        });
    });
});


Route::group(['middleware' => 'api'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('paginate', [UserController::class, 'paginate']);
        });

        Route::group(['prefix' => 'role'], function () {
            Route::put('{role}/switch', [SwitchRoleController::class, 'index']);
            Route::get('paginate', [RoleController::class, 'paginate']);
        });

        Route::group(['prefix' => 'permission'], function () {
            Route::get('paginate', [PermissionController::class, 'paginate']);
        });

        Route::group(['prefix' => 'team'], function () {
            Route::post('/', [TeamController::class, 'store']);
            Route::get('accept-invite/{token}', [TeamworkAuthController::class, 'acceptInvite']);
            Route::get('paginate', [TeamController::class, 'paginate']);
            Route::get('{team_id}', [TeamController::class, 'show']);
        });

        Route::apiResource('user', UserController::class)->except(['destroy']);
        Route::apiResource('role', RoleController::class);
        Route::apiResource('permission', PermissionController::class);
    });
});
