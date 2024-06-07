<?php

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

if (!function_exists('denyAllAccess')) {
    /**
     * @param string $permission
     * @return void
     */
    function denyAllAccess(string $permission): void
    {
        if (backpack_user()->hasPermissionTo($permission, config('backpack.base.guard')))
            return;

        CRUD::denyAllAccess();
    }
}
