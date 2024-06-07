<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    fn(): RedirectResponse => redirect(
        backpack_url(backpack_auth()->check() ? 'dashboard' : 'login')
    )
);
