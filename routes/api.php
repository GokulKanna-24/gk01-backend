<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ModuleController;
use App\Http\Controllers\API\RoleController;

Route::get('/', function() {
    return "API";
});

Route::middleware(['auth_sso'])->group(function () {
    Route::post('get-user-details', [AuthController::class, 'get_user_details']);

    Route::get('get-modules', [ModuleController::class, 'get_modules']);
    Route::get('get-roles', [RoleController::class, 'get_roles']);
    Route::get('get-permissions', [RoleController::class, 'get_permissions']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('logout-all', [AuthController::class, 'logoutAll']);