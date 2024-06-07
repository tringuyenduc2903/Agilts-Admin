<?php

/*
|--------------------------------------------------------------------------
| Custom Backpack Routes
|--------------------------------------------------------------------------
|
| This route file is loaded automatically by Backpack\Base.
| Routes you generate using Backpack\Generators will be placed here.
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
        Route::post('switch-layout', TablerSwitchLayout::class)
            ->name('tabler.switch.layout');
        Route::crud('customer', CustomerCrudController::class);
    });
