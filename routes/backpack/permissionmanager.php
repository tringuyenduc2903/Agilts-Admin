<?php

/*
|--------------------------------------------------------------------------
| Backpack\PermissionManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\PermissionManager package.
|
*/

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::prefix(config('backpack.base.route_prefix', 'admin'))
    ->middleware(array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    ))
    ->group(function () {
        Route::crud('role', RoleCrudController::class);
        Route::crud('user', UserCrudController::class);
    });
