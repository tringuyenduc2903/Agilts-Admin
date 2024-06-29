<?php

namespace App\Http\Controllers;

class VerifyEmailController extends \Backpack\CRUD\app\Http\Controllers\Auth\VerifyEmailController
{
    public function __construct()
    {
        $this->middleware(backpack_middleware());
        $this->middleware('signed')->only('verifyEmail');
        $this->middleware('throttle:' . config('backpack.base.email_verification_throttle_access'))->only('resendVerificationEmail');

        if (!backpack_users_have_email()) {
            abort(500, trans('backpack::base.no_email_column'));
        }

        // where to redirect after the email is verified
        $this->redirectTo = $this->redirectTo ?? backpack_url('dashboard');
    }
}
