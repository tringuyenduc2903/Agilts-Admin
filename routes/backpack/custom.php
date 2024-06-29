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
        if (config('backpack.base.setup_email_verification_routes', false)) {
            Route::get('email/verify', [VerifyEmailController::class, 'emailVerificationRequired'])
                ->middleware(backpack_middleware())
                ->name('verification.notice');
            Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyEmail'])
                ->middleware(backpack_middleware())
                ->name('verification.verify');
            Route::post('email/verification-notification', [VerifyEmailController::class, 'resendVerificationEmail'])
                ->middleware(backpack_middleware())
                ->name('verification.send');
        }

        Route::post('switch-layout', TablerSwitchLayout::class)
            ->name('tabler.switch.layout');
        Route::crud('customer', CustomerCrudController::class);
        Route::crud('branch', BranchCrudController::class);
    });
