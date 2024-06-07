<?php

namespace App\Http\Middleware;

use Alert;
use App\Enums\Permission;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckIfAdmin extends \Backpack\CRUD\app\Http\Middleware\CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if (backpack_auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        } else if (!$this->checkIfUserIsAdmin()) {
            backpack_auth()->logout();
            Alert::error(trans('backpack::base.unauthorized'))->flash();

            return $this->respondToUnauthorizedRequest($request);
        } else
            return $next($request);
    }

    /**
     * Answer to unauthorized access request.
     *
     * @param Request $request
     * @return Response|RedirectResponse
     */
    private function respondToUnauthorizedRequest(Request $request): Response|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            return redirect()->guest(backpack_url('login'));
        }
    }

    /**
     * Checked that the logged in user is an administrator.
     *
     * --------------
     * VERY IMPORTANT
     * --------------
     * If you have both regular users and admins inside the same table, change
     * the contents of this method to check that the logged in user
     * is an admin, and not a regular user.
     *
     * Additionally, in Laravel 7+, you should change app/Providers/RouteServiceProvider::HOME
     * which defines the route where a logged in user (but not admin) gets redirected
     * when trying to access an admin route. By default it's '/home' but Backpack
     * does not have a '/home' route, use something you've built for your users
     * (again - users, not admins).
     *
     * @return bool
     */
    private function checkIfUserIsAdmin(): bool
    {
        return backpack_user()->hasPermissionTo(
            Permission::DASHBOARD,
            config('backpack.base.guard')
        );
    }
}
